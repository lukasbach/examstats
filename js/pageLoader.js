/**
 * Created by lbach on 11.08.2016.
 */
function loadPage(name) {
    console.log("Loading page " + name);
    $(".page.show").slideUp( "slow" );
    $(".page").removeClass("show");
    $(".page." + name).addClass("show").slideDown( "slow" );

    if (name !== "newstatistic-1" && name !== "newstatistic-2"
        && name !== "newstatistic-3") {
        $(".creationsteps").slideUp("slow");
    } else if (name === "newstatistic-1") {
        $(".creationsteps").slideDown("slow");
    }
}