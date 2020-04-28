<?php

declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: Yogurt
 *
 * @category        Module
 * @package         yogurt
 * @author          Marcello Brandão aka  Suico, Mamba, LioMJ  <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use XoopsModules\Yogurt;
use Xmf\Request;

const NBAUDIO = 'nbAudio';

$GLOBALS['xoopsOption']['template_main'] = 'yogurt_audios.tpl';
require __DIR__ . '/header.php';

$controller = new Yogurt\AudioController($xoopsDB, $xoopsUser);

/**
 * Fetching numbers of groups friends videos pictures etc...
 */
$nbSections = $controller->getNumbersSections();

$start = Request::getInt('start', 0, 'GET');

/**
 * Fetching numbers of groups friends videos pictures etc...
 */
$nbSections = $controller->getNumbersSections();

/**
 * Criteria for Audio
 */
$criteriaUidAudio = new Criteria('uid_owner', $controller->uidOwner);
$criteriaUidAudio->setStart($start);
$criteriaUidAudio->setLimit($helper->getConfig('audiosperpage'));

/**
 * Get all audios of this user and assign them to template
 */
$audiosArray = [];
$audios      = $controller->getAudio($criteriaUidAudio);

/**
 * If there is no audio files show in template lang_noaudioyet
 */
if (isset($nbSections[NBAUDIO]) && 0 === $nbSections[NBAUDIO]) {
    //    $lang_noaudioyet = _MD_YOGURT_NOTHINGYET;
    //    $xoopsTpl->assign('lang_nopicyet', $lang_noaudioyet);
    echo '<script>alert("Please add some audio files here")</script>';
} else {
    /**
     * Lets populate an array with the data from the pictures
     */
    $i = 0;
    foreach ($audios as $audio) {
        $audiosArray[$i]['audio_id']     = $audio->getVar('audio_id', 's');
        $audiosArray[$i]['title']        = $audio->getVar('title', 's');
        $audiosArray[$i]['author']       = $audio->getVar('author', 's');
        $audiosArray[$i]['description']  = $audio->getVar('description', 's');
        $audiosArray[$i]['filename']     = $audio->getVar('filename', 's');
        $audiosArray[$i]['uid_owner']    = $audio->getVar('uid_owner', 's');
        $audiosArray[$i]['date_created'] = formatTimeStamp($audio->getVar('date_created', 's'));
        $audiosArray[$i]['date_updated'] = formatTimeStamp($audio->getVar('date_updated', 's'));
        $xoopsTpl->assign('audios', $audiosArray);
        $i++;
    }
}

$xoopsTpl->assign('audios', $audios);
$nbAudio = $nbSections[NBAUDIO] ?? 0;
try {
    $audiosArray = $controller->assignAudioContent($nbAudio, $audios);
} catch (\RuntimeException $e) {
}

if (is_array($audiosArray) && count($audiosArray) > 0) {
    $xoopsTpl->assign('audios', $audiosArray);
    $audio_list = [];
    foreach ($audiosArray as $audio_item) {
        $audio_list[] = XOOPS_UPLOAD_URL . '/yogurt/audio/' . $audio_item['filename']; // . ' | ';
    }
    //$audio_list = substr($audio_list,-2);
    $xoopsTpl->assign('audio_list', $audio_list);
} else {
    $xoopsTpl->assign('lang_noaudioyet', _MD_YOGURT_NOAUDIOYET);
}
$pageNav = '';
if (isset($nbSections[NBAUDIO]) && $nbSections[NBAUDIO] > 0) {
    $pageNav = $controller->getAudiosNavBar($nbSections[NBAUDIO], $helper->getConfig('audiosperpage'), $start, 2);
}
$xoTheme->addScript('https://unpkg.com/wavesurfer.js');

//meta language names
$xoopsTpl->assign('lang_meta', _MD_YOGURT_META);
$xoopsTpl->assign('lang_title', _MD_YOGURT_META_TITLE);
$xoopsTpl->assign('lang_album', _MD_YOGURT_META_ALBUM);
$xoopsTpl->assign('lang_artist', _MD_YOGURT_META_ARTIST);
$xoopsTpl->assign('lang_year', _MD_YOGURT_META_YEAR);

//form actions
$xoopsTpl->assign('lang_delete', _MD_YOGURT_DELETE);
$xoopsTpl->assign('lang_editdesc', _MD_YOGURT_EDIT_DESC);
$xoopsTpl->assign('lang_makemain', _MD_YOGURT_MAKEMAIN);

//Form Submit
$xoopsTpl->assign('lang_selectaudio', _MD_YOGURT_AUDIO_SELECT);
$xoopsTpl->assign('lang_authorLabel', _MD_YOGURT_AUDIO_AUTHOR);
$xoopsTpl->assign('lang_titleLabel', _MD_YOGURT_AUDIO_TITLE);
$xoopsTpl->assign('lang_submitValue', _MD_YOGURT_AUDIO_SUBMIT);
$xoopsTpl->assign('lang_addaudios', _MD_YOGURT_AUDIO_ADD);

$xoopsTpl->assign('width', $helper->getConfig('width_tube'));
$xoopsTpl->assign('height', $helper->getConfig('height_tube'));
$xoopsTpl->assign('player_from_list', _MD_YOGURT_PLAYER);
$xoopsTpl->assign('lang_audiohelp', sprintf(_MD_YOGURT_AUDIO_ADD_HELP, $helper->getConfig('maxfilesize')));
$xoopsTpl->assign('max_youcanupload', $helper->getConfig('maxfilesize'));

$xoopsTpl->assign('lang_mysection', _MD_YOGURT_MYAUDIOS);
$xoopsTpl->assign('section_name', _MD_YOGURT_AUDIOS);

//Page Navigation
$xoopsTpl->assign('pageNav', $pageNav);

require __DIR__ . '/footer.php';
require dirname(__DIR__, 2) . '/footer.php';
