/**
 * Created by lbach on 27.09.2016.
 */
var retrieveStatistic = function (statName) {
    $(".input-loadName").val(statName);
    $.ajax({
        url: "server/check_has_password.php",
        method: "POST",
        data: {
            "idname": statName
        },

        success: function(returnValue) {
            if (returnValue === "true") {
                loadPage("enter-password");
            }
            else if (returnValue === "false") {
                // Does not have an password, directly load data
                // (yeah messy code duplication I know)
                $.ajax({
                    url: "server/retrieve_statistic.php",
                    method: "POST",
                    data: {
                        "idname": statName,
                        "password": ""
                    },

                    success: function(returnValue) {
                        try {
                            var statistic = JSON.parse(returnValue);
                            parser.importResult(statistic.resultdata);
                            $(".stat-name").html(statistic.title);
                            loadView(statistic["hide_student_ids"] === "0",
                                statistic["title"]);
                            loadPage("statisticView");
                        } catch (e) {
                            if (e instanceof SyntaxError) {
                                // Error message
                                alert(returnValue);
                            }
                        }
                    }
                })
            }
            else {
                alert(returnValue);
            }
        }
    })
}