<?php
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */
namespace Sre\SmartReportingEngine\src;


class Configs
{

    const SRE__DEFAULT__USER__ = "";
    const SRE__DEFAULT__PASS__ = "";
    const SRE__DEFAULT__HOST__ = "localhost";
    const SRE__DEFAULT__DB__ = "";
    const SRE__DEFAULT_LOGIN_PAGE_ = "";
    const SRE__DEFAULT_LOGOUT_PAGE_ = "";
    const SRE_DEFAULT_SESSION_NAME = "";

    const SRE__ALLOWED_REPORT_LANGUAGES__ = [
        "en",
        "de",
        "ar",
        "es",
        "fr",
        "it"
    ];
    const SRE__DEFAULT_REPORT_LANGUAGE__ = "en";

   

    //directory names

    const SRE__Auto__Replace__Reports__ = 0; 
    const SRE__LANGUAGE__ = "en"; 
    const SRE_DEFAULT_LAYOUT_ = "AlignLeft";
    const SRE_TEST_MODE = 0;
}
