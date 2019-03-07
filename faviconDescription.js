
{
	"masterPicture": "TODO: Path to your master picture",
	"iconsPath": "/",
	"design": {
		"ios": {
			"masterPicture": {
				"type": "inline",
			},
			"pictureAspect": "noChange",
			"assets": {
				"ios6AndPriorIcons": false,
				"ios7AndLaterIcons": false,
				"precomposedIcons": true,
				"declareOnlyDefaultIcon": true
			},
			"appName": "Pharmacie"
		},
		"desktopBrowser": {},
		"windows": {
			"pictureAspect": "whiteSilhouette",
			"backgroundColor": "#17cebd",
			"onConflict": "override",
			"assets": {
				"windows80Ie10Tile": true,
				"windows10Ie11EdgeTiles": {
					"small": false,
					"medium": true,
					"big": false,
					"rectangle": false
				}
			},
			"appName": "Pharmacie"
		},
		"androidChrome": {
			"masterPicture": {
				"type": "inline",
			},
			"pictureAspect": "shadow",
			"themeColor": "#17cebd",
			"manifest": {
				"name": "Pharmacie",
				"display": "standalone",
				"orientation": "notSet",
				"onConflict": "override",
				"declared": true
			},
			"assets": {
				"legacyIcon": false,
				"lowResolutionIcons": false
			}
		},
		"safariPinnedTab": {
			"pictureAspect": "silhouette",
			"themeColor": "#17cebd"
		}
	},
	"settings": {
		"compression": 5,
		"scalingAlgorithm": "Mitchell",
		"errorOnImageTooSmall": false,
		"readmeFile": false,
		"htmlCodeFile": true,
		"usePathAsIs": false
	}
}