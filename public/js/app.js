//#region //! BEGIN FUNCTION UNTUK GUDANG
//! END FUNCTION UNTUK GUDANG
//#endregion

//#region  //! BEGIN FUNCTION UNTUK ROLE
function getCheckedCheckbox() {
    let checklist = document.querySelectorAll(".checkbox-gudang:checked");
    let list_gudang = [];
    checklist.forEach((checkbox) => {
        list_gudang.push(checkbox.value);
    });
    let list_gudang_json = JSON.stringify(list_gudang);

    getProjectFromGudang(list_gudang_json);
}
//! END FUNCTION UNTUK ROLE
//#endregion

//#region //! BEGIN FUNCTION UNTUK PROJECT

//#region //! BEGIN FUNCTION UNTUK PERUSAHAAN

//#endregion

//#region  //! BEGIN FUNCTION UNTUK INBOUND

//#endregion

//#region //! BEGIN FUNCTION UNTUK ITEM

//#endregion
