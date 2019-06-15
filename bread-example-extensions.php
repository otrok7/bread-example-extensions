<?php
/**
Plugin Name: bread-ext
Plugin URI: none
Description: Show how to add functionality to Bread
Author: Ron B
Version: 0.0.1
*/

add_filter( 'Bread_Meeting_Fields', 'addMeetingFields' );   // Control the fields that are returned when retrieving meetings
                                                            // All field names are available for use in templates
add_filter( 'Bread_Enrich_Meeting_Data', 'enrichMeetingData', 10, 2 );  // Add computed fields to the shortcodes in templates
add_filter( 'Bread_Section_Shortcodes', 'sectionShortcodes', 10, 3 );   // Add shortcodes to front-page, last-page and custom-page

function addMeetingFields( $fields ) {
    array_push($fields,'public_transport');  // These are two fields defined in my bmlt meeting data.
    array_push($fields,'format_comments');
    return $fields;
};
function enrichMeetingData($value, $formats_by_key) {  // make the string of language formats available for use in template
    $enFormats = explode ( ",", $value['formats'] );
    $langs = array();
    foreach($enFormats as $format_key) {
        $format_key = trim($format_key);
        if (! isset($formats_by_key[$format_key])) {
            continue;
        }
        if ($formats_by_key[$format_key]['format_type_enum']=='LANG') {
            $langs[] = $formats_by_key[$format_key]['name_string'];
        }
    }
    $value['lang_format_names'] = implode('/',$langs);
    return $value;
}
function sectionShortcodes($section_shortcodes, $areas, $formats_used) {  // In this case, the meetings are printed
                                                                          // on a 4-column portrait-mode page,
                                                                          // but we want to fold the meeting list 3 ways, and
                                                                          // that is how our front page should be printed.
                                                                          // So we create short codes with the MPDF directives.
    $section_shortcodes['[Page-Break-L]'] = '<pagebreak orientation=\"L\"/>';
    $section_shortcodes['[Columns-3]'] = '<columns column-count="3" vAlign="justify" column-gap=\"8\"/>';
    return $section_shortcodes;
}
?>