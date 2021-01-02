require("./bootstrap");
import iro from '@jaames/iro';  // https://iro.js.org/guide.html

$(document).ready(function(){
    console.log("game.js init");

    var colorPicker = new iro.ColorPicker("#picker");
    colorPicker.on(["color:init", "color:change"], function(color){
        // Show the current color in different formats
        values.innerHTML = [
          "hex: " + color.hexString,
          "rgb: " + color.rgbString,
        //   "hsl: " + color.hslString,
        ].join("<br>");
      });
});