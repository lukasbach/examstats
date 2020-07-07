<?php
$loadStat = false;
if (isset($_GET["s"])) {
    $loadStatName = $_GET["s"];
    $loadStat = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ExamStats</title>

    <script src="https://code.jquery.com/jquery-3.1.0.js"
            integrity="sha256-slogkvB1K3VOkzAI8QITxV3VzpOnkeNVsKvtkYLMjfk="
            crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css"
          href="vendor/semantic/dist/semantic.min.css">
    <script src="vendor/semantic/dist/semantic.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>

    <script src="js/Parser.js"></script>
    <script src="js/Statistic.js"></script>
    <script src="js/pageLoader.js"></script>
    <script src="js/buttons.js"></script>
    <script src="js/loadView.js"></script>
    <script src="js/retrieveStatistic.js"></script>

    <script>
        var retrieveBaseUrl = "http://examstats.lukasbach.com/?s=";

        $(document).ready(function() {
            $(".page").hide();
            <?php
            if ($loadStat) {
                echo "retrieveStatistic(\"$loadStatName\");";
            } else {
                echo "loadPage(\"newstatistic-1\");";
            }
            ?>
            
        });
    </script>

</head>
    <body>


        <div class="ui container" style="margin-top:120px">
            <h1 class="ui header">ExamStats</h1>
            <p>Basic tool for generating statistical data from exam results.</p>

            <div class="ui three item menu">
                <a class="active item run-gotoCreateStat run-tab">Create new statistic</a>
                <a class="item run-gotoLoadStat run-tab">Load statistic</a>
                <a class="item run-gotoAbout run-tab">About</a>
            </div>

            <div class="ui divider"></div>

            <div class="creationsteps-container">
                <div class="ui ordered steps three item creationsteps">
                    <div class="active step">
                        <div class="content">
                            <div class="title">Defining input data</div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="content">
                            <div class="title">Check refactored data</div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="content">
                            <div class="title">Generate and publish</div>
                        </div>
                    </div>
                </div>

                <div class="ui divider"></div>
            </div>

            <div class="page newstatistic-1">
                <div class="ui form">
                    <div class="field">
                        <label>Text</label>
                        <textarea class="input-data"></textarea>

                        <div class="ui message">
                            <div class="header">
                                Copy and Paste
                            </div>
                            <p>Just copy and paste the text, which contains all
                            the test result data, in the box above. Each result
                            should be in a seperate line, trash lines may stay.
                            The script will automatically try to format the data
                            and read the results.</p>
                        </div>
                    </div>

                    <h2 class="ui header">Parser default options</h2>

                    <div class="two fields">
                        <div class="field">
                            <label>Line Delemiter (which seperates the lines. \n
                            is a line break.)</label>
                            <input type="text" name="linedel" value="\n"
                                   class="input-linebreak" />
                        </div>
                        <div class="field">
                            <label>In line delemiter (which seperates the values
                            inside the line.)</label>
                            <i>Currently not selectable.</i>
                        </div>
                    </div>

                    <div class="two fields">
                        <div class="field">
                            <label>Maximum points</label>
                            <input type="text" name="maxpoints" value="200"
                                class="input-maxpoints"/>
                        </div>
                        <div class="field">
                            <label>Minimum points</label>
                            <input type="text" name="minpoints" value="0"
                                class="input-minpoints"/>
                        </div>
                    </div>

                    <div class="two fields">
                        <div class="field">
                            <label>Regular expression describing the grade value</label>
                            <input type="text" name="gradeRegex" value="(\d(\.|,)\d{1,2})(?!.)"
                                class="input-graderegex"/>
                        </div>
                        <div class="field">
                            <label>Regular expression describing the student id</label>
                            <input type="text" name="studentIdRegex" value="\d{5,12}"
                                class="input-studentidregex"/>
                        </div>
                    </div>

                    <div class="field">
                        <label>Strings indicating the student of a certain line
                            was dismissed (may be more than one, seperated by spaces)</label>
                        <input type="text" name="dismissedStrings" value="dismissed abgemeldet"
                            class="input-dismissedstrings"/>
                    </div>
                    <button class="ui button run-firstStep">Next step</button>
                </div>
            </div>


            <div class="page newstatistic-2">
                <h2 class="ui header">Parsed data</h2>

                <div class="ui message">
                    <div class="header">
                        Check the data
                    </div>
                    <p>You can now check the data if it was properly processed.
                    Red marked lines were not identified and are seen as trash
                    lines. They will be ignored in the result.</p>
                </div>

                <button class="ui button run-secondStep">Next step</button>
                <table class="ui celled table resultcheck">
                    <thead>
                    <tr>
                        <th>Line source</th>
                        <th>Student id</th>
                        <th>Grade</th>
                        <th>Points</th>
                        <th>Dismissed?</th>
                        <th>Identified?</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button class="ui button run-secondStep">Next step</button>
            </div>

            <div class="page newstatistic-3">
                <h2 class="ui header">Publishing</h2>

                <div class="ui message">
                    <p>You can now choose wether to publish the data and
                    which parts of it. You can also just generate the view
                    without saving it, if you are just interested in the
                    results.</p>
                </div>

                <div class="ui form">

                    <div class="two fields">
                        <div class="field">
                            <label>Password for accessing the result (leave empty
                                if access should be public)</label>
                            <input type="password" name="psw"
                                   class="input-psw"/>
                        </div>
                        <div class="field">
                            <label>Name of the result</label>
                            <input type="text" name="resultname"
                                   class="input-resultname"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>Unique name (will be used in the url)</label>
                            <input type="text" name="uniquename"
                                   class="input-uniquename"/>
                        </div>
                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" tabindex="1"
                                       class="hidden input-hidestudentids">
                                <label>Allow student to search for result
                                    by student id</label>
                            </div>
                        </div>
                    </div>

                </div>

                <button class="ui button run-preview">Show statistic without publishing</button>
                <button class="ui button run-publish">Publish</button>

            </div>


            <div class="page load-1">
                <h2 class="ui header">Loading a statistic</h2>

                <div class="ui message">
                    <p>Input the unique name that you or someone else has used to
                        publish the statistic with to retrieve an already generated
                        statistic.</p>
                </div>

                <div class="ui form">
                    <div class="two fields">
                        <div class="field">
                            <input type="text" name="loadName" placeholder="Name of the statistic"
                                   class="input-loadName"/>
                        </div>
                        <div class="field">
                            <button class="ui button run-loadCheckPsw">Next step</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page enter-password">
                <h2 class="ui header">Loading a statistic</h2>

                <div class="ui message">
                    <p>The statistic that you are trying to retrieve is secured
                    with a password. Enter it to view the statistic.</p>
                </div>

                <div class="ui form">
                    <div class="two fields">
                        <div class="field">
                            <input type="password" name="loadPsw" placeholder="password"
                                   class="input-loadPsw"/>
                        </div>
                        <div class="field">
                            <button class="ui button run-verifyPassword">Load statistic</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="page statisticView ui container">
            <div class="ui center aligned container">
            <h1 class="stat-name"></h1>

                <div class="ui statistics four">
                    <div class="statistic">
                        <div class="value stat-studentcount"></div>
                        <div class="label">
                            Participants
                        </div>
                    </div>

                    <div class="statistic">
                        <div class="value stat-avgGrade"></div>
                        <div class="label">
                            Average grade
                        </div>
                    </div>

                    <div class="statistic">
                        <div class="value stat-avgPoints"></div>
                        <div class="label">
                            Average points
                        </div>
                    </div>

                    <div class="statistic">
                        <div class="value stat-maxPoints"></div>
                        <div class="label">
                            Maximum points achieved
                        </div>
                    </div>
                </div>
            </div>

            <div class="ui divider"></div>

            <div class="ui container center aligned">
                    <div class="ui action input search-for-result-bar">
                        <input type="text" placeholder="student id" class="input-mystudentidsearch">
                        <button class="ui teal right labeled icon button run-findmyresult">
                            <i class="search icon"></i>
                            Find result
                        </button>
                    </div>

                    <button class="ui button run-sharestatistic">Share this statistic</button>
            </div>

            <div class="ui divider"></div>

            <div class="ui big red progress stat-failure-rate">
                <div class="bar"><div class="progress"></div></div>
                <div class="label">Failure rate</div>
            </div>

            <div class="ui divider"></div>

            <canvas class="stat-chart-gradebars" width="400" height="200"></canvas>

            <div class="ui divider"></div>

            <canvas class="stat-chart-pointsbars" width="400" height="200"></canvas>

            <div class="ui divider"></div>

            <div class="ui horizontal statistics">
                <div class="statistic">
                    <div class="value stat-dismissedCount"></div>
                    <div class="label">
                        students dismissed
                    </div>
                </div>
                <div class="statistic">
                    <div class="value stat-bestGrade"></div>
                    <div class="label">
                        Best grade
                    </div>
                </div>
                <div class="statistic">
                    <div class="value stat-worstGrade"></div>
                    <div class="label">
                        Worst grade
                    </div>
                </div>
                <div class="statistic">
                    <div class="value stat-bestPoints"></div>
                    <div class="label">
                        Best result
                    </div>
                </div>
                <div class="statistic">
                    <div class="value stat-worstPoints"></div>
                    <div class="label">
                        Worst result
                    </div>
                </div>
            </div>


        </div>

        <div class="page about ui container">
        <p>ExamStats is a basic tool which allows easy generation of statistics based on the result data. You can easily
        copy and paste a table of results into this tool or write a simple list of them. The tool tries to read the
        list and convert it automatically into useful data, so that no manual refactorization of the results is required.</p>

        <p>Statistic can be saved and shared with other people, once generated. Statistics can also be secured using a password,
        if not everybody should be capable of seeing the statistic.</p>

        <p>This tool was made by <a href="http://lukasbach.com">Lukas Bach</a> and is free to use for everybody.</p>
        </div>

        <div class="page showmyresult ui container">
            <div class="ui center aligned container">
                <div class="ui horizontal statistics">
                    <div class="statistic">
                        <div class="value stat-mystudentid">1939135</div>
                        <div class="label">
                            my student id
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value stat-mygrade">1.3</div>
                        <div class="label">
                            my grade
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value stat-mypoints">34</div>
                        <div class="label">
                            my points
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="height: 40px"></div>

        <div class="ui modal share-stat">
            <i class="close icon"></i>
            <div class="header">
                Share this statistic
            </div>
            <div class="image content">
                <div class="description">
                    <p>Share this URL with others to make the statistic accessible to them:</p>
                    <div class="ui form">
                        <input type="text" class="share-url">
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui button cancel">Close</div>
            </div>
        </div>

        <div class="ui modal my-result">
            <i class="close icon"></i>
            <div class="header">
                Your results.
            </div>
            <div class="image content">
                <div class="description">
                <div class="ui horizontal statistics">
                    <div class="statistic">
                        <div class="value stat-mystudentid">1939135</div>
                        <div class="label">
                            my student id
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value stat-mygrade">1.3</div>
                        <div class="label">
                            my grade
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value stat-mypoints">34</div>
                        <div class="label">
                            my points
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui button cancel">Close</div>
            </div>
        </div>
    </body>
</html>