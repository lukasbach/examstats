/**
 * Created by lbach on 11.08.2016.
 */
function loadView(allowSearch, title) {
    stat.load(parser.giveResult());
    var data = stat.giveHighlights();

    console.log(data);


    $(".stat-studentcount").html(data.studentCount);
    $(".stat-avgGrade").html(Math.round(data.gradeAverage * 100) / 100);
    $(".stat-avgPoints").html(Math.round(data.pointsAverage * 100) / 100);
    $(".stat-maxPoints").html(data.maximumPoints);
    $(".stat-dismissedCount").html(data.dismissedCount);
    $(".stat-bestGrade").html(data.minimumGrade);
    $(".stat-worstGrade").html(data.maximumGrade);
    $(".stat-bestPoints").html(data.maximumPoints);
    $(".stat-worstPoints").html(data.minimumPoints);

    $('.stat-failure-rate').progress({
        percent: data.failureRate * 100
    });

    if (allowSearch) {
        $("search-for-result-bar").show();
    } else {
        $(".search-for-result-bar").hide();
    }

    $(".run-sharestatistic").show();

    var gradebars = new Chart($(".stat-chart-gradebars"), {
        type: 'bar',
        data: {
            labels: data.grades,
            datasets: [{
                label: 'people that reached this grade',
                data: data.gradesListCount,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    var pointsbars = new Chart($(".stat-chart-pointsbars"), {
        type: 'bar',
        data: {
            labels: data.points,
            datasets: [{
                label: 'people that achieved that many points',
                data: data.pointsListCount,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}