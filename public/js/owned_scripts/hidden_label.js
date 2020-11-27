function hideHiddenLabel() {
    let hidden_th = document.querySelectorAll(".hidden_label");
    let classname = "";

    hidden_th.forEach((element) => {
        classname = element.className.split(" ");

        // $(`tr .${classname[1]}`).attr("style", "display:none");
        $(`table tr .${classname[1]}`).attr("style", "display:none");
    });
}
