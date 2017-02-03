// JavaScript Document
//<[CDATA[ 
var eLore_wrap = document.getElementById('eLore_wrap'); 
var aImg = new Array('http://www.jb51.net/upload/112_jpg_thumb.jpg','http://www.jb51.net/upload/123_jpg_thumb.jpg','http://www.jb51.net/upload/112_jpg_thumb.jpg', 'http://www.jb51.net/upload/123_jpg_thumb.jpg'); 
var iImg = 0; 
var iA = 0; 
window.onload = function() { 
    eLore_createD(); 
} 
function eLore_createD() { 
    if (iImg==10) { 
        eLore_wrap.innerHTML = ''; 
        iImg = 0; 
    } 
    if (iImg==0) { 
        eLore_wrap.innerHTML += '<div class="eLore_out" style="right:0px; background:url(' + aImg[iA] + ') -680px top no-repeat;">H</div>\n'; 
    } 
    /* 
    var sDiv = '<div class="eLore_img" style="left:' + (720-iImg*80) + 'px; background:url(' + aImg[iA] + ') -720px top no-repeat;">' + iImg++ + '</div>\n'; 
    eLore_wrap.innerHTML += sDiv; 
    */ 
    var oDiv = document.createElement('div'); 
    oDiv.className = 'eLore_img'; 
    oDiv.style.right = iImg*80 +'px'; 
    oDiv.style.background = 'url(' + aImg[iA] + ') -720px top no-repeat'; 
    //oDiv.appendChild(document.createTextNode(iImg)); 
    eLore_wrap.appendChild(oDiv); 
    iImg++; 
    eLore_move(); 
} 
function eLore_move(){ 
    var oDiv = eLore_wrap.getElementsByTagName('div'); 
    for (var i=1; i<oDiv.length; i++) { 
        var iBgpx = parseInt(oDiv[i].style.backgroundPosition); 
        if (iBgpx<i*80-760) { 
            var iMovePx = Math.floor((760-i*80+iBgpx)/15); 
            oDiv[i].style.backgroundPosition = iBgpx - iMovePx + 'px top'; 
        } else { 
            oDiv[i].style.backgroundPosition = '-' + (760- i*80) + 'px top'; 
        } 
    } 
    if (iImg<10) { 
        setTimeout('eLore_createD()','50'); 
    } else if (parseInt(oDiv[10].style.backgroundPosition)<40) { 
        setTimeout('eLore_move()','50'); 
    } else { 
        iA = ++iA==aImg.length ? 0 : iA; 
        setTimeout('eLore_createD()','2000'); 
    } 
} 
//]]> 