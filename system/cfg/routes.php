<?php
$routes = '
{
    "routes": {
        "static":	{
            "collects/traditional": {
              "http_method":"get",
              "controller": "collects",
              "action": "trad",
              "view": "collects"
            },
              "collects/contemporary": {
              "http_method":"get",
              "controller": "collects",
              "action": "contempo",
              "view": "collects"
            },
              "prefaces/contemporary": {
              "http_method":"get",
              "controller": "prefaces",
              "action": "contempo",
              "view": "prefaces"
            },
              "prefaces/traditional": {
              "http_method":"get",
              "controller": "prefaces",
              "action": "trad",
              "view": "prefaces"
            }
          },
          "named": {

          }
    }
}';
