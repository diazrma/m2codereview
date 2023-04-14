'use strict'

const vscode = require('vscode');
const scanning = require('./src/scanning');

/**
 * @param {vscode.ExtensionContext} context
 */
const activate = (context) => {
	
	let disposable = vscode.commands.registerCommand('m2codereview.run', function () {
		scanning.searchFiles();
		vscode.window.showInformationMessage("Scan Finish");
	});

	context.subscriptions.push(disposable);
}


const deactivate = () => {}

module.exports = {
	activate,
	deactivate
}
