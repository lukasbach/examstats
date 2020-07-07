/**
 * Created by lbach on 10.08.2016.
 */

var Parser = function() {
    /**
     * Stores the raw input data, which shall be processed.
     * @type String
     */
    this.input = null;

    /**
     * Stores the result array, as soon as it is constructed. When constructed,
     * it stores objects with the following fields:
     *  - studentid (usually the matrikelnumber or some identifier)
     *  - points
     *  - grade
     * @type Array
     */
    this.result = [];

    /**
     * Indicates wether the result has been calculated or not.
     * @type {boolean}
     */
    this.parsed = false;

    /**
     * Saves the delemiter used to seperate lines.
     */
    this.delemiter = false;
};

/**
 * Loads data in from a given string.
 * @param {String} input is the input string which contains the data.
 */
Parser.prototype.load = function(input) {
    this.input = input;
};

/**
 * Parse the data. This has to be called after load();
 * @returns {boolean}
 */
Parser.prototype.parse = function() {
    if (this.input == null) return false;

    var result = this.result;
    var thisObj = this;

    $.each(this.input.split(Parser.settings.lineBreak), function(index, line) {
        // Find corresponding delemiter
        var delemiter;
        $.each(Parser.settings.delemiters, function(i, del) {
            if (line.includes(del)) delemiter = del;
        });
        thisObj.delemiter = delemiter;

        // Check line parts
        var lineObject = {};
        lineObject.dismissed = false;
        lineObject.line = line;
        $.each(line.split(delemiter), function(index, linePart) {
            // Check if grade
            if (Parser.settings.gradeRegex.test(linePart)) {
                lineObject.grade = parseFloat(linePart.replace(",", "."));
                return true;
            }

            // Check if points
            if ($.isNumeric(linePart)
                && linePart >= Parser.settings.minPoints
                && linePart <= Parser.settings.maxPoints) {
                lineObject.points = parseInt(linePart);
                return true;
            }

            // Check if dismissed string
            var isDismissed = false;
            $.each(Parser.settings.dismissedStrings, function(i, str) {
                if (linePart.includes(str)) isDismissed = true;
            });
            if (isDismissed) {
                lineObject.dismissed = true;
                return true;
            }

            // Check if is student id
            if (Parser.settings.studentidRegex.test(linePart)) {
                lineObject.studentid = linePart;
                return true;
            }
        });

        if (!("grade" in lineObject) && !("points" in lineObject)) {
            lineObject.identified = false;
        } else {
            lineObject.identified = true;
        }

        result.push(lineObject);
    });

    this.sortOut();
};

/**
 * Improve data set quality by sorting out lines that seem odd.
 */
Parser.prototype.sortOut = function() {
    if (this.giveResult() == false) return false;

    var del = this.delemiter;

    // Count average line size
    var lineSize = 0;
    $.each(this.result, function(i, line) {
        lineSize += line.line.split(del).length;
    });
    if (lineSize == 0) return;
    var avgLineSize = lineSize / this.result.length;

    // Sort too large / small lines out
    $.each(this.result, function(i, line) {
        var len = line.line.split(del).length;
        if (len < avgLineSize - 2 || len > avgLineSize + 2) {
            line.identified = false;
        }
    });
};

/**
 * Return the result of the parsing process. Has to be called after parse();
 * If no results are set, this will return false.
 * @returns {*}
 */
Parser.prototype.giveResult = function() {
    if (this.result == []) return false;
    return this.result;
};

/**
 * Overwrite the internal result object with the provided one. That is useful
 * for the case that the information has been saved to the server and should
 * now be restored.
 * @param result is the new result stored in this parser object.
 */
Parser.prototype.importResult = function(result) {
    this.result = result;
}

/**
 * Parsing settings
 * @type {{lineBreak: string, delemiters: string[], maxPoints: number, minPoints: number, gradeRegex: RegExp, studentidRegex: RegExp, dismissedStrings: string[]}}
 */
Parser.settings = {
    lineBreak: "\n",
    delemiters: [" ", "\t", ";"],
    maxPoints: 200,
    minPoints: 0,
    gradeRegex: /(\d(\.|,)\d{1,2})(?!.)/g,
    studentidRegex: /\d{5,12}/g,
    dismissedStrings: ["dismissed", "abgemeldet"] //TODO befriedigend, mangelhaft... mappen
};