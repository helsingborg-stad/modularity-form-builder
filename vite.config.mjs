import { createViteConfig } from "vite-config-factory";

const entries = {
	"js/modularity-form-builder-admin":
		"./source/js/modularity-form-builder-admin.ts",
	"js/modularity-form-builder-front":
		"./source/js/modularity-form-builder-front.ts",
	"js/modularity-form-builder-referer":
		"./source/js/modularity-form-builder-referer.ts",
	"css/modularity-form-builder": "./source/sass/modularity-form-builder.scss",
};

export default createViteConfig(entries, {
	outDir: "assets/dist",
	manifestFile: "manifest.json",
});
