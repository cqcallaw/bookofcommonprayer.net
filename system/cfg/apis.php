<?php
// get, put, post, delete, patch
$apis = '
{
    "routes": {
        "static": {
            "daily_office": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office"
            },
            "daily_office/readings": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_readings"
            },
            "daily_office/holy_days": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_holy_days"
            },
            "daily_office/holy_day_list": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_holy_day_list"
            },
            "daily_office/holy_day_readings": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_holy_days_readings"
            },
            "daily_office/references": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_references"
            },
            "daily_office/canticles": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_canticles"
            },
            "daily_office/comfortable_words": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_comfortable_words"
            },
            "daily_office/comfortable_words_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_comfortable_words_es"
            },
            "daily_office/antiphon": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_antiphon"
            },
            "daily_office/antiphon_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_antiphon_es"
            },
            "daily_office/collect_of_day": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_cod"
            },
            "daily_office/additional_collect": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_additional_collect"
            },
            "daily_office/additional_collect_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_additional_collect_es"
            },
            "daily_office/noonday_collect": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_noonday_collect"
            },
            "daily_office/noonday_collect_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_noonday_collect_es"
            },
            "daily_office/noonday_scripture": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_noonday_scripture"
            },
            "daily_office/noonday_scripture_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_noonday_scripture_es"
            },
            "daily_office/compline_collect": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_compline_collect"
            },
            "daily_office/compline_collect_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_compline_collect_es"
            },
            "daily_office/compline_scripture": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_compline_scripture"
            },
            "daily_office/compline_scripture_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_compline_scripture_es"
            },
            "daily_office/compline_prayer": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_compline_prayer"
            },
            "daily_office/compline_prayer_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_compline_prayer_es"
            },
            "daily_office/prayer_for_mission": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_prayer_for_mission"
            },
            "daily_office/prayer_for_mission_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_prayer_for_mission_es"
            },
            "daily_office/conclusion": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_conclusion"
            },
            "daily_office/conclusion_es": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_conclusion_es"
            },
            "daily_office/psalms": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "daily_office_psalms"
            },
            "lectionary": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "lectionary"
            },
            "lectionary/readings": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "lectionary_readings"
            },
            "lectionary/preface": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "lectionary_preface"
            },
            "lectionary/holy_day_list": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "lectionary_holy_day_list"
            },
            "lectionary/holy_days": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "lectionary_holy_days"
            },
            "lectionary/holy_day_readings": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "lectionary_holy_day_readings"
            },
            "search/get": {
                "http_method": "post",
                "accept": "application/json",
                "controller": "search"
            }
        },
        "named": {
            "user/:id/:postid": {
                "http_method": "get",
                "accept": "text/html",
                "controller": "user",
                "action": "getPostById"
            }
        }
    }
}';
