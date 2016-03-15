(function() {   
    tinymce.create('tinymce.plugins.chinese', { //注意这里有个 chinese   
        init : function(ed, url) {   
            ed.addButton('chinese', { //注意这一行有一个 chinese   
                title : 'chinese',   
                image : url+'/orhon-logo.png', //注意图片的路径 url是当前js的路径   
                onclick : function() {   
                     ed.selection.setContent('<span class="chinese_word" contentEditable="true" style="overflow: scroll; width:300px; height:300px; font-family: serif; border:2px solid red; transform-origin:left top; transform:matrix(0,1,1,0,0,0);">chinese</span>'); //这里是你要插入到编辑器的内容，你可以直接写上广告代码  
                }   
            });   
        },   
        createControl : function(n, cm) {   
            return null;   
        },   
    }); 
    tinymce.PluginManager.add('chinese', tinymce.plugins.chinese); //注意这里有两个 chinese   
})();  