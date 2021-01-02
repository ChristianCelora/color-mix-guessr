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

    $("#game-timer").ready(function(){
        // var seconds = $(this).data("seconds");
        var seconds = window.seconds_left;
        var timer = $("#game-timer");
        var x = setInterval(function() {
            let seconds_str = (seconds < 10) ? "0"+seconds : seconds;
            timer.children("#time-left").html("00:"+seconds_str);            
            console.log(seconds);
            seconds -= 1;
            // If the count down is finished, write some text
            if (seconds < 0) {
                clearInterval(x);
                timer.children("#time-left").html("TIME'S UP!");
            }
        }, 1000);
    });    
});