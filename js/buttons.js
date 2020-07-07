/**
 * Created by lbach on 11.08.2016.
 */

var parser = new Parser;
var stat = new Statistic();

$(document).ready(function() {
    $(".run-firstStep").click(function () {
        // Load variables
        Parser.settings.maxPoints = parseInt($(".input-maxpoints").val());
        Parser.settings.minPoints = parseInt($(".input-minpoints").val());
        Parser.settings.gradeRegex = new RegExp($(".input-graderegex").val());
        Parser.settings.studentidRegex = new RegExp($(".input-studentidregex").val());
        Parser.settings.dismissedStrings = $(".input-dismissedstrings").val().split(" ");

        parser.load($(".input-data").val());
        parser.parse();

        $.each(parser.giveResult(), function(i, line) {
            $("<tr></tr>")
                .addClass(!line.identified ? "error" : "")
                .addClass(line.dismissed ? "warning" : "")
                .append("<td>" + line.line + "</td>")
                .append("<td>" + line.studentid + "</td>")
                .append("<td>" + line.grade + "</td>")
                .append("<td>" + line.points + "</td>")
                .append("<td>" + line.dismissed + "</td>")
                .append("<td>" + line.identified + "</td>")
                .appendTo($(".resultcheck"));
        });
        
        loadPage("newstatistic-2");
        $(".creationsteps .step.active")
            .removeClass("active")
            .addClass("completed");
        $($(".creationsteps .step").get(1))
            .addClass("active");
    });


    $(".run-secondStep").click(function () {
        loadPage("newstatistic-3");
        $(".creationsteps .step.active")
            .removeClass("active")
            .addClass("completed");
        $($(".creationsteps .step").get(2))
            .addClass("active");
    });


    $(".run-preview").click(function () {
        $(".stat-name").html("Generated statistic");
        loadView();
        $(".run-sharestatistic").hide();
        loadPage("statisticView");
    });

    $(".run-publish").click(function () {
        $.ajax({
            url: "server/save_statistic.php",
            method: "POST",
            data: {
                "idname": $(".input-uniquename").val(),
                "title": $(".input-resultname").val(),
                "password": $(".input-psw").val(),
                "hide_student_ids": !$(".input-hidestudentids").parent().hasClass("checked"),
                "json": JSON.stringify(parser.giveResult())
            },

            success: function(returnValue) {
                if (returnValue === "success") alert("yay");
                else {
                    alert(returnValue);
                }
            }
        })
    });

    $(".run-loadCheckPsw").click(function() {
        retrieveStatistic($(".input-loadName").val())
    });

    $(".run-verifyPassword").click(function () {
        $.ajax({
            url: "server/retrieve_statistic.php",
            method: "POST",
            data: {
                "idname": $(".input-loadName").val(),
                "password": $(".input-loadPsw").val()
            },

            success: function(returnValue) {
                try {
                    var statistic = JSON.parse(returnValue).resultdata;
                    parser.importResult(statistic);
                    loadView();
                    loadPage("statisticView");
                } catch (e) {
                    if (e instanceof SyntaxError) {
                        // Error message
                        alert(returnValue);
                    }
                }
            }
        })
    });

    $(".run-findmyresult").click(function () {
        var mystudentid = $(".input-mystudentidsearch").val();

        var myresult = null;

        $.each(parser.giveResult(), function(i, e) {
            if (e.studentid == mystudentid) {
                myresult = e;
                return true;
            }
        });

        if (myresult == null) alert("Result not found");
        else {
            $(".stat-mystudentid").html(myresult.sudentid);
            $(".stat-mygrade").html(myresult.grade);
            $(".stat-mypoints").html(myresult.points);
            //loadPage("showmyresult");
            $(".modal.my-result").modal("show");
        }
    });

    $(".run-sharestatistic").click(function() {
        $(".share-url").val(retrieveBaseUrl + $(".input-loadName").val());
        $(".modal.share-stat").modal("show");
    });

    $(".run-tab").click(function () {
        $(".run-tab").removeClass("active");
        $(this).addClass("active");

        if (!$(this).hasClass("run-gotoCreateStat")) {
            $(".creationsteps-container").slideUp("slow");
        } else {
            $(".creationsteps-container").slideDown("slow");
        }
    });

    $(".run-gotoLoadStat").click(function () {
        loadPage("load-1");
    });

    $(".run-gotoAbout").click(function () {
        loadPage("about");
    });

    $(".run-gotoCreateStat").click(function () {
        $(".creationsteps .step")
            .removeClass("active")
            .removeClass("completed");
        $($(".creationsteps .step").get(0))
            .addClass("active");
        loadPage("newstatistic-1");
    });

    $('.ui.checkbox')
        .checkbox()
    ;
});