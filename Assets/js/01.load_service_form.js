// Add sub-form for api service integration field.
Mautic.apiServices_loadServiceForm = function (e){
    Mautic.apiService_json_editor();
    // hide built in header title, we add our own with JSON Editor
    mQuery('#integration_details_featureSettings').parent('div').children('h4').hide();
}
