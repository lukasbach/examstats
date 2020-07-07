/**
 * Created by lbach on 10.08.2016.
 */

var Statistic = function() {
    /**
     * Contains an array of data, which was parsed by a Parser.
     */
    this.data = null;

    /**
     * Parser object
     */
    this.parser = null;
};

Statistic.prototype.load = function(parsedData) {
    this.data = parsedData;
};

/**
 * Calculate statistical data about the given dataset.
 */
Statistic.prototype.giveHighlights = function() {
    var highlights = {
        studentCount: 0,
        dismissedCount: 0,
        unidentifiedCount: 0,

        minimumPoints: Parser.settings.maxPoints,
        maximumPoints: 0,
        pointsCount: 0,
        pointsAverage: 0,

        minimumGrade: 5,
        maximumGrade: 0,
        gradeCount: 0,
        gradeAverage: 0,

        failureRate: 0,

        gradeMap: {},
        pointsMap: {},

        grades: [1.0, 1.3, 1.7, 2.0, 2.3, 2.7, 3.0, 3.3, 3.7, 4.0, 5.0],
        gradesListCount: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        points: [],
        pointsListCount: []

    };

    $.each(this.data, function(index, line) {
        if (line.identified) {
            highlights.studentCount++;

            if (line.dismissed) highlights.dismissedCount++;
            else {
                highlights.minimumPoints = line.points < highlights.minimumPoints ?
                    line.points : highlights.minimumPoints;
                highlights.maximumPoints = line.points > highlights.maximumPoints ?
                    line.points : highlights.maximumPoints;
                highlights.pointsCount += line.points;

                highlights.minimumGrade = line.grade < highlights.minimumGrade ?
                    line.grade : highlights.minimumGrade;
                highlights.maximumGrade = line.grade > highlights.maximumGrade ?
                    line.grade : highlights.maximumGrade;
                highlights.gradeCount += line.grade;

                if (line.grade in highlights.gradeMap)
                    highlights.gradeMap[line.grade]++;
                else highlights.gradeMap[line.grade] = 1;

                if (line.points in highlights.pointsMap)
                    highlights.pointsMap[line.points]++;
                else highlights.pointsMap[line.points] = 1;

                // TODO only count grades and points that are actually numeric
            }
        } else {
            highlights.unidentifiedCount++;
        }
    });

    highlights.pointsAverage = highlights.pointsCount / highlights.studentCount;
    highlights.gradeAverage = highlights.gradeCount / highlights.studentCount;
    highlights.failureRate = highlights.gradeMap[5.0] / highlights.studentCount;

    for (var i = 0; i < highlights.grades.length; i++) {
        highlights.gradesListCount[i] = highlights.gradeMap[highlights.grades[i]];
    }
    for (var i = highlights.minimumPoints; i <= highlights.maximumPoints; i++) {
        highlights.points[i] = i;
        highlights.pointsListCount[i] = highlights.pointsMap[i];
    }

    return highlights;
};