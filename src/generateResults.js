const fs = require("fs");

exports.create = (element) => {
  fs.truncate("results.txt", 0, (err) => {
    if (err) throw err;
    fs.appendFile("results.txt", element + "\n", (err) => {
      if (err) throw err;
      console.log(err);
    });
  });
};
