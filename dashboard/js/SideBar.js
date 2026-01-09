function DisplaySideBarFun(Title) {
    if (Title != "BarCodeItems") {
        $("#MainAsideBar").css("display", "block");
        $("#ssbar").removeClass("margin-50")
        $("#body").removeClass("sidebar-collapse");

    }
    else {
        $("#MainAsideBar").css("display", "none");
        $("#ssbar").addClass("margin-50")
        $("#body").addClass("sidebar-collapse");

    }

}