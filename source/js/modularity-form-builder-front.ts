import GetLocation from './front/get-location';
import HandleConditions from './front/handle-conditions';

const formVerificationDelayInMilliseconds = 5000;

document.querySelectorAll<HTMLInputElement>('.modularity-t-field').forEach((field) => {
	window.setTimeout(() => {
		// Submission.php expects the elapsed delay in milliseconds as the verification value.
		field.value = formVerificationDelayInMilliseconds.toString();
	}, formVerificationDelayInMilliseconds);
});

const FormBuilderFront = {
	GetLocation,
	HandleConditions,
};
