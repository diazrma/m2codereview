"use strinct";

const vscode = require("vscode");
const template = require("./templates/files");
const results = require("./generateResults");

exports.searchFiles = () => {
  vscode.window.showInformationMessage("Scanning Files...");
  template.files().forEach((fileTemplate) => {
    vscode.workspace.findFiles(fileTemplate, "").then((files) => {
      files.forEach((file) => {
        if (file.fsPath) {
          console.log(file.fsPath);
          results.create(file.fsPath);
        }
      });
    });
  });
};
