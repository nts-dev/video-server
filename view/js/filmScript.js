/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var scriptMainPrimaryLayout = audioSubtitleTabbar.tabs("scripts").attachLayout('1C');
        scriptMainPrimaryLayout.cells("a").hideHeader()
        
var scriptMainLayout = scriptMainPrimaryLayout.cells("a").attachLayout('2U');

scriptMainLayout.cells('a').hideHeader()
scriptMainLayout.cells('b').hideHeader()

scriptMainLayout.cells('a').attachURL(baseURL +"app/tinyMceDisplay_scripts.php?id=contentIframe&name=contentIframe", false,
        {template_content: ''});