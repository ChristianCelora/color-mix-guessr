require("./bootstrap");
import iro from '@jaames/iro';  // https://iro.js.org/guide.html

$(document).ready(function(){
    console.log("game.js init");

    var colorPicker = new iro.ColorPicker("#picker");
    var solution_enabled = true;
    var div_selected_color = $("#color-picker .input-color");

    colorPicker.on(["color:init", "color:change"], function(color){
        if(solution_enabled){
            // Show the current color in different formats
            values.innerHTML = [
                "hex: " + color.hexString,
                "rgb: " + color.rgbString,
            //   "hsl: " + color.hslString,
            ].join("<br>");            
            div_selected_color.css("background-color", ""+color.rgbString);
        }
    });

    $("#game-timer").ready(function(){
        // var seconds = $(this).data("seconds");
        var seconds = window.seconds_left;
        var timer = $("#game-timer");
        var x = setInterval(function() {
            let seconds_str = (seconds < 10) ? "0"+seconds : seconds;
            timer.children("#time-left").html("00:"+seconds_str);            
            seconds -= 1;
            // If the count down is finished, write some text
            if (seconds < 0) {
                clearInterval(x);
                timer.children("#time-left").html("00:00");
                
                solution_enabled = false;
                let guess_color = colorPicker.color.rgb;
                $.ajax({
                    url: window.route_api_solution,
                    type: "POST",
                    dataType: "json",
                    data: {
                        "game_id": window.game_id,
                        "guess": {
                            "red": guess_color["r"],
                            "green": guess_color["g"],
                            "blue": guess_color["b"]
                        }, 
                    },
                    encode: true
                }).done(function(res){
                    console.log(res);
                    // Create div for solution
                    if("solution" in res){
                        let solution = res["solution"];
                        $("#solution div.input-color").css("background-color", "#"+solution["hex"]);
                        $("#solution div.color-label").html(solution["name"]);
                        $("#solution").show();
                        $("#solution-placeholder").hide();
                        $("#solution-placeholder").removeClass("d-flex");
                    }
                    // Shows score
                });
            }
        }, 1000);
    });    
});