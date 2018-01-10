<?php

namespace ModularityFormBuilder\Helper;

class YoutubeUploader
{
    /**
     * Upload a locally stored video to YouTube
     * @param  string   $videoPath   Path to the video file
     * @param  string   $title       Video title
     * @param  string   $description Video description
     * @param  string   $category    Numeric YouTube category
     * @param  array    $tags        List with video tags
     * @return string                String containing YouTube URL
     */
    public static function uploadVideo($videoPath, $title, $description, $category, $tags = array())
    {
        $key = get_option('options_mod_form_access_token');
        $appName = 'YouTube uploader';
        $oauth2ClientID = get_option('options_mod_form_client_id');
        $oauth2ClientSecret = get_option('options_mod_form_secret');
        $scopes = array('https://www.googleapis.com/auth/youtube.upload',
                        'https://www.googleapis.com/auth/youtube',
                        'https://www.googleapis.com/auth/youtubepartner');
        $uploadedVideo = null;

        try {
            // Client init
            $client = new \Google_Client();
            $client->setApplicationName($appName);
            $client->setClientId($oauth2ClientID);
            $client->setAccessType('offline');
            $client->setAccessToken($key);
            $client->setScopes($scopes);
            $client->setClientSecret($oauth2ClientSecret);

            if ($client->getAccessToken()) {

                // Check to see if our access token has expired. If so, get a new one and save it to DB
                if ($client->isAccessTokenExpired()) {
                    $currentToken = $client->getAccessToken();
                    $client->refreshToken($currentToken['refresh_token']);
                    update_option('options_mod_form_access_token', $client->getAccessToken());
                }

                $youtube = new \Google_Service_YouTube($client);

                // Create a snipet with title, description, tags and category id
                $snippet = new \Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle($title);
                $snippet->setDescription($description);
                $snippet->setCategoryId($category);
                $snippet->setTags($tags);

                // Create a video status with privacy status. Options are "public", "private" and "unlisted".
                $status = new \Google_Service_YouTube_VideoStatus();
                $status->setPrivacyStatus('private');

                // Create a YouTube video with snippet and status
                $video = new \Google_Service_YouTube_Video();
                $video->setSnippet($snippet);
                $video->setStatus($status);

                // Size of each chunk of data in bytes. Setting it higher leads faster upload (less chunks,
                // for reliable connections). Setting it lower leads better recovery (fine-grained chunks)
                $chunkSizeBytes = 1 * 1024 * 1024;

                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);

                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtube->videos->insert("status,snippet", $video);

                // Create a MediaFileUpload object for resumable uploads.
                $media = new \Google_Http_MediaFileUpload(
                    $client,
                    $insertRequest,
                    'video/*',
                    null,
                    true,
                    $chunkSizeBytes
                );
                $media->setFileSize(filesize($videoPath));

                // Read the media file and upload it chunk by chunk.
                $status = false;
                $handle = fopen($videoPath, "rb");
                while (!$status && !feof($handle)) {
                    $chunk = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }

                fclose($handle);

                // Video has successfully been upload
                if ($status->status['uploadStatus'] == 'uploaded') {
                    $uploadedVideo = 'https://www.youtube.com/watch?v=' . $status['id'];

                    // Remove the video file from our server
                    if (file_exists($videoPath)) {
                        unlink($videoPath);
                    }
                }

                // If you want to make other calls after the file upload, set setDefer back to false
                $client->setDefer(true);

            } else {
                error_log('Problems creating the Google_Client');
            }

        } catch(\Google_Service_Exception $e) {
            error_log("Caught Google service Exception ".$e->getCode(). " message is ".$e->getMessage());
            error_log("Stack trace is ".$e->getTraceAsString());
        } catch (\Exception $e) {
            error_log("Caught Google service Exception ".$e->getCode(). " message is ".$e->getMessage());
            error_log("Stack trace is ".$e->getTraceAsString());
        }

        return $uploadedVideo;
    }
}
