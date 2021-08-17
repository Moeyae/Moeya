var lazyLoadInstance = new LazyLoad({
    elements_selector: ".entry-content img"
});

function b2prettyPrint(){
    if(b2_global.prettify_load == '0') return
	let classArr =  document.getElementsByTagName('pre');//替换标签class
	if(typeof(classArr)=="object"){		
		for(var i = 0;i < classArr.length;i++){			
			if(classArr[i].className.indexOf('prettyprint') <= -1){
				classArr[i].className = "prettyprint linenums";		
			}
		}
	}
	prettyPrint();
}

window.onload = b2prettyPrint()

var b2SingleMeta = new Vue({
    el:'#post-meta',
    data:{
        following:false,
        self:true,
        showMeta:false,
        locked:false,
        postData:[]
    },
    mounted(){
        if(!b2_global.author_id) return
        this.$http.post(b2_rest_url+'checkFollowing','user_id='+b2_global.author_id+'&post_id='+b2_global.post_id).then(res=>{
            this.self = res.data.self
            this.following = res.data.following
            this.postData = res.data
            b2DsBox.user = res.data.current_user
            b2DsBox.author = res.data.author
            b2Ds.data = res.data.ds_data

            if(typeof postAuthor == 'object'){

                postAuthor.following = res.data.following

            }

            this.$nextTick(()=>{
                b2tooltip('.b2tooltipbox')
                if (typeof window.contentFooter !== 'undefined') window.contentFooter.updateSticky()
            })
            
            payCredit.user = res.data.current_user
            payCredit.author = res.data.author
        })
    },
    methods:{
        followingAc(){
            
            if(!b2token){
                login.show = true
            }else{
                this.$http.post(b2_rest_url+'AuthorFollow','user_id='+b2_global.author_id).then(res=>{
                    this.following = !this.following
                }).catch(err=>{
                    Qmsg['warning'](err.response.data.message,{html:true});
                })
            }
        },
        dmsg(){
            
            if(!b2token){
                login.show = true
            }else{
                b2Dmsg.userid = b2_global.author_id
                b2Dmsg.show = true
            }
        },
        scroll(){
            this.$scrollTo('#download-box', 300, {offset: -50})
        }
    }
})

Vue.component('post-gg', {
    props: ['show','title','content'],
    template: b2_global.post_gg,
    methods:{
        close(){
            this.$emit('close')
        },
    }
})

var postGG = new Vue({
    el:'#post-gg',
    data:{
        show:false,
        title:'',
        content:''
    },
    mounted(){
        this.getGGData()
    },
    methods:{
        getGGData(){
            this.$http.post(b2_rest_url+'getPostGG','post_id='+b2_global.post_id).then(res=>{
                if(res.data.title || res.data.content){
                    this.title = res.data.title
                    this.content = res.data.content
                    this.close()
                }
            })
        },
        close(){
            this.show = !this.show
        },
    }
})

var postType5 = new Vue({
    el:'.post-style-5-top',
    data:{
        videos:[],
        user:'',
        player:'',
        index:0,
        show:true,
        title:'',
        showViews:false,
        url:''
    },
    mounted(){
        
        if(!this.$refs.postType5) return
        let post_id = this.$refs.postType5.getAttribute('data-id')

        let order_id = b2getCookie('b2_guest_buy_'+post_id+'_v')
        if(order_id){
            order_id = JSON.parse(order_id)
            order_id = order_id[0]['order_id']
        }
        this.$http.post(b2_rest_url+'getPostVideos','post_id='+post_id+'&order_id='+order_id).then(res=>{
            this.videos = res.data.videos
            postVideoList.videos = this.videos
            
            if(this.videos.length <= 1){
                this.$refs.videoList.style.display = 'none'
            }
            if(this.user.allow){
                this.show = false
            }
            this.user = res.data.user
            this.list = res.data.list
            this.title = res.data.title
            if(this.videos.length > 0){

                if(this.user.allow){
                    this.url = this.videos[0].url
                }else{
                    this.url = this.videos[0].view
                    if(this.url){
                        this.showViews = true
                    }else{
                        this.showViews = false
                    }
                }

                this.player = new DPlayer({
                    container: document.getElementById('post-style-5-player'),
                    screenshot: false,
                    video: {
                        url: this.url,
                        pic: this.videos[0].poster,
                        type:'auto',
                    },
                    contextmenu:[],
                    airplay:true,
                    mutex:true,
                    hotkey:true,
                    preload:res.data.auto === '1' ? 'auto' : 'none',
                    logo:b2_global.default_video_logo,
                    autoplay:res.data.auto && this.url ? true : false
                })

                document.querySelector('.post-video-list').style.height = document.querySelector('.post-style-5-video-box').clientHeight+'px'

                if(this.videos.length >= 1){
                    //视频播放结束，切换下一条
                    this.player.on('ended',()=>{
                        if(this.index+1 < this.videos.length){
                            this.index = this.index + 1
                            postVideoList.index = this.index
                            this.select(this.index)
                        }
                    })

                    this.player.on('playing',()=>{
                        document.getElementById('post-style-5-player').querySelectorAll('.dplayer-video-current')[0].style="object-fit:contain"
                    })
                }
            }
            b2playerInit()
        })

    },
    methods:{
        select(index){
            this.index = index
            postVideoList.index = this.index
            document.getElementById('post-style-5-player').querySelectorAll('.dplayer-video-current')[0].style="object-fit:cover"
            if(this.user.allow){
                this.url = this.videos[index].url
            }else{
                this.url = this.videos[index].view
            }
            this.player.switchVideo({
                url:this.url,
                pic:this.videos[index].poster
            })

            if(this.user.allow){
                this.player.play()
            }else{
                this.show = true
            }

        },
        goComment(){
            this.$scrollTo('#comments', 300, {offset: -50})
        },
        showAc(){
            this.show = !this.show
            if(this.show === true){
                this.player.pause()
            }
        },
        listSlide(){
            var flkty = new Flickity(this.$refs.videoListIn, {
                cellAlign: 'left',
                contain: true
              });
        },
        play(){
            this.show = false
            this.player.play()
        },
        pay(guest){
            
            if(!b2token && !guest){
                login.show = true
            }else{
                b2DsBox.data = {
                    'title':this.title,
                    'order_price':this.user.role.value,
                    'order_type':'v',
                    'post_id':b2_global.post_id
                }
                b2DsBox.show = true;
                b2DsBox.showtype = 'normal'
            }
        },
        credit(){
            
            if(!b2token){
                login.show = true
            }else{
                payCredit.data = {
                    'title':this.title,
                    'order_price':this.user.role.value,
                    'order_type':'v',
                    'post_id':b2_global.post_id
                }
                payCredit.show = true;
            }
        },
        login(type){
            login.show = true
            login.loginType = type
        }
    }
})

function b2playerInit(){
    let player = document.querySelectorAll('.dplayer-video');
    if(player.length > 0){
        for (let i = 0; i < player.length; i++) {
            player[i].setAttribute('x5-video-player-type','h5-page')
            player[i].setAttribute('x5-video-player-fullscreen','true')
            player[i].setAttribute('playsinline','true')
            player[i].setAttribute('webkit-playsinline','true')
        }
    }
    
}

//播放远程视频
function b2SingleVideo(){
    let boxes = document.querySelectorAll('.entry-content .content-video-box');

    if(boxes.length > 0){
        boxes.forEach(e => {
            e.onclick = (even)=>{
                let url = even.target.parentNode.getAttribute('data-video-url');
                if(url){
                    axios.post(b2_rest_url+'getVideoHtml','url='+encodeURIComponent(url)).then((res)=>{
                        if(res.data.indexOf('class="smartideo"') !== -1){
                            e.innerHTML = res.data
                        }
                    })
                }
            }
        });
    }
}
b2SingleVideo()

//文章内部图片点击放大
function b2ImgZooming(sele){
    if(!b2_global.show_slider || b2_global.show_slider == '0') return

    let att = document.querySelectorAll('.attachment img')
    for (let _i = 0; _i < att.length; _i++) {
        b2zoom.listen(att[_i])
    }

    var imgs = document.querySelectorAll(sele)

    for (let i = 0; i < imgs.length; i++) {
        if(
            imgs[i].className.indexOf('po-img-big') !== -1 || 
            imgs[i].className.indexOf('alignnone') !== -1 || 
            imgs[i].className.indexOf('alignleft') !== -1 ||
            imgs[i].className.indexOf('alignright') !== -1 ||
            imgs[i].className.indexOf('aligncenter') !== -1 ||
            imgs[i].className.indexOf('gallery-image') !== -1 ||
            imgs[i].className.indexOf('size-full') !== -1 ||
            imgs[i].className.indexOf('wp-image-') !== -1 
        ){
            b2zoom.listen(imgs[i]);
        }
        
    }

    var img2 = document.querySelectorAll('.entry-content figure img')
    for (let i = 0; i < img2.length; i++) {
        b2zoom.listen(img2[i]);
    }

    var img3 = document.querySelectorAll('.entry-content > p > img')
    for (let i = 0; i < img3.length; i++) {
        b2zoom.listen(img3[i]);
    }

}
document.addEventListener('DOMContentLoaded', function () {
    b2ImgZooming('.entry-content img')
})

//附件下载，复制解压码
function b2FileDown(){
    let code = document.querySelectorAll('.entry-content .file-down-pass span');
    code.forEach(e => {
        e.onclick = (ev)=>{

            let input = ev.target.firstElementChild;
            input.select()

            if(document.execCommand('copy')){

                ev.target.firstChild.data = b2_global.copy.success
                setTimeout(()=>{
                    ev.target.firstChild.data = b2_global.copy.text
                },1000)

            }else{
                ev.target.firstChild.data = b2_global.copy.error
            }

            window.getSelection().removeAllRanges();
        }
    });
}
b2FileDown()

//显示隐藏短代码内容
function showHideContent(){
    let box = document.querySelectorAll('.entry-content .content-show-roles');

    if(box.length > 0){

        let h_axios = axios
        if(b2token){
            h_axios.defaults.headers.common['Authorization'] = 'Bearer ' + b2token
        }

        let order_id = b2getCookie('b2_guest_buy_'+b2_global.post_id+'_w')

        if(order_id){
            order_id = JSON.parse(order_id)
            order_id = order_id[0]['order_id']
        }
        h_axios.post(b2_rest_url+'getHiddenContent','id='+b2_global.post_id+'&order_id='+order_id).then((res)=>{

            if(res.status == 200){
                for (let i = 0; i < box.length; i++) {

                    if(typeof(res.data) == 'object'){
                        box[i].innerHTML = res.data[i]
                        box[i].parentNode.parentNode.style.height = 'auto'
                    }else{
                        box[i].innerHTML = res.data
                    }

                    Vue.nextTick(()=>{
                        b2prettyPrint()
                        b2VideoReset()
                        b2SidebarSticky()
                        if (typeof window.contentFooter !== 'undefined') window.contentFooter.updateSticky()
                    })
                }
            }

            setTimeout(()=>{
                b2AfterInnterHtml()
            },100)
        })
     
    }
}
showHideContent()

function b2AfterInnterHtml(){
    b2FileDown()
    b2SingleVideo()
    b2ImgZooming('.entry-content .content-show-roles img')
}

Vue.component('poster-box', {
    props: ['show'],
    template:b2_global.poster_box,
    data(){
        return {
            poster:'',
            isWeixin:false,
            data:'',
            loadedjs:false,
            locked:false,
            showLong:false,
            thumb:'',
            logo:''
        }
    },
    computed:{
        userData(){
            return this.$store.state.userData;
        }
    },
    methods:{
        ready(){
            this.isWeixin = b2isWeixin()
            this.data = b2_global.poster_data

            if(b2token){
                this.data.link = new URL(this.data.link)
                this.data.link.searchParams.set('ref', this.userData.user_code)
                this.data.link = this.data.link.href
            }

            const qr = new QRious({
                value: this.data.link,
                size:100,
                level:'L'
            });
            this.data.qrcode = qr.toDataURL('image/jpeg')
        },
        close(val){
            this.$emit('close-form',val)
        },
        openWin(url,type){
            if(type == 'weibo'){
                url = url+'&pic='+this.data.thumb
            }else{
                url = url+'&pics='+this.data.thumb
            }

            openWin(url,type,500,500)
        },
        getbase64(){
            if(this.locked) return;
            this.locked = true 
            this.$http.post(b2_rest_url+'urlToBase64','url='+this.data.logo).then(res=>{
                this.logo = res.data;
                this.$http.post(b2_rest_url+'urlToBase64','url='+this.data.thumb).then(res=>{
                    this.thumb = res.data;
                    this.$nextTick(()=>{
                        setTimeout(()=>{
                            this.html2canvas()
                        }, 100);
                    })
                });
            });
        },
        html2canvas(){
            var dom = this.$refs.posterContent,
                w = dom.clientWidth,
                h = dom.clientHeight;

            html2canvas(dom,{
                background: '#ffffff',
                taintTest: false,
                useCORS: true,
                scale: 2,
                with:w,
                height:h,
                onrendered:(canvas)=>{
                    canvas.mozImageSmoothingEnabled = false;
                    canvas.webkitImageSmoothingEnabled = false;
                    canvas.msImageSmoothingEnabled = false;
                    canvas.imageSmoothingEnabled = false;

                    let img = Canvas2Image.convertToJPEG(canvas, w*2, h*2)
                    this.poster = img.src
                }
            });
            
        },
        base64ToBlob(code) {
            var parts = code.split(';base64,');
            var contentType = parts[0].split(':')[1];
            var raw = window.atob(parts[1]);
            var rawLength = raw.length;

            var uInt8Array = new Uint8Array(rawLength);

            for (var i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }
            return new Blob([uInt8Array], {type: contentType});
        },
    },
    watch:{
        show(val){
            if(val && !this.loadedjs){
                this.ready()
                b2loadScript(b2_global.site_info.site_uri+'/Assets/fontend/library/html2canvas.min.js','',()=>{
                    b2loadScript(b2_global.site_info.site_uri+'/Assets/fontend/library/canvas2image.min.js','',()=>{
                        this.loadedjs = true
                        let img = new Image()
                        img.crossOrigin = '';
                        
                        img.onload = ()=>{
                            setTimeout(() => {
                                this.html2canvas()
                            }, 100);
                        }

                        img.src = this.data.thumb;
                    })
                })
            }
        }
    }
})

var posterBox = new Vue({
    el:'#poster-box',
    data:{
        show:false,
        data:[]
    },
    methods:{
        close(val){
            this.show = val
        }
    }
})

var postVideoTable = new Vue({
    el:'.post-video-table',
    data:{
        table:'content'
    },
    methods:{
        tab(type){
            this.table = type
        }
    },
    watch:{
       table(val){
            if(val === 'comment'){
                document.querySelector('#post-5-list').style.display = 'none'
                document.querySelector('.single-article').style.display = 'none'
                document.querySelector('.comments-box').style.display = 'block'
            }else if(val === 'list'){
                document.querySelector('#post-5-list').style.display = 'block'
                document.querySelector('.single-article').style.display = 'none'
                document.querySelector('.comments-box').style.display = 'none'
            }else{
                document.querySelector('#post-5-list').style.display = 'none'
                document.querySelector('.single-article').style.display = 'block'
                document.querySelector('.comments-box').style.display = 'none'
            }
       }
    }
})

var postVideoList = new Vue({
    el:'#post-5-list',
    data:{
        videos:[],
        index:0
    },
    methods:{
        select(i){
            postType5.select(i)
        }
    }
})

//内页底部
var b2ContentFooter = new Vue({
    el:'.content-footer',
    data:{
        showPoster:false,
        postData:[],
        locked:false
    },
    mounted(){
        let footer = document.querySelector('.post-content-footer');
        if(footer && B2ClientWidth <= 768){
            footer.classList.remove('post-content-footer');
            footer.style.display = 'flex'
        }else{
            setTimeout(() => {
                if(footer && B2ClientWidth > 768){
                    window.contentFooter = new StickySidebar('.post-content-footer', {
                        topSpacing: 0,
                    });
                }
            }, 10);
        }
    },
    methods:{
        openPoster(){
            posterBox.show = true;
        },
        goComment(){
            this.$scrollTo('#comments', 300, {offset: -50})
        },
        postFavoriteAc(){
            
            if(!b2token){
                login.show = true
            }else{
                if(this.locked == true) return
                this.locked = true

                this.$http.post(b2_rest_url+'userFavorites','post_id='+b2_global.post_id).then(res=>{
                    if(res.data == true){
                        this.postData.favorites_isset = true
                    }else{
                        this.postData.favorites_isset = false
                    }
                    this.locked = false
                }).catch(err=>{

                    Qmsg['warning'](err.response.data.message,{html:true});
                    this.locked = false
                })
            }
        },
        vote(type){
            
            if(!b2token){
                login.show = true
            }else{
                if(this.locked == true) return
                this.locked = true

                this.$http.post(b2_rest_url+'postVote','type='+type+'&post_id='+b2_global.post_id).then(res=>{

                    this.postData.up = parseInt(this.postData.up) + parseInt(res.data.up)
                    this.postData.down = parseInt(this.postData.down) + parseInt(res.data.down)
                    
                    if(res.data.up > 0){
                        this.postData.up_isset = true
                    }else{
                        this.postData.up_isset = false
                    }

                    if(res.data.down > 0){
                        this.postData.down_isset = true
                    }else{
                        this.postData.down_isset = false
                    }

                    this.locked = false
                }).catch(err=>{

                    Qmsg['warning'](err.response.data.message,{html:true});
                    this.locked = false
                })
            }
        }
    }
})

//评论框
var b2Comment = new Vue({
    el:'#respond',
    data:{
        data:[],
        content:'',
        show:{
            info:false,
            smile:false,
            image:false
        },
        focus:false,
        //图片上传
        parentId:0,
        progress:0,
        locked:false,
        commentData:{
            imgUrl:'',
            imgId:''
        },
        subLocked:false,
        sketchpad:'',
        drawing:false,
        sketchpadOpt:{
            color:'#121212',
            penSize:'2'
        },
        b2token:false
    },
    computed:{
        userData(){
            return this.$store.state.userData
        },
        canImg(){
            return this.$store.state.canImg
        }
    },
    mounted(){
        if(this.$refs.respond){
            this.b2token = b2token
            this.resetUserInfo()
            setTimeout(()=>{
                autosize(this.$refs.textarea_box);
                this.buildSketchpad()
                b2tooltip('.comment-type button')
                b2tooltip('.d-replay button')
            })
            
        }
    },
    methods:{
        buildSketchpad(){
            if(!this.$refs.sketchpad) return
            this.sketchpad = new Sketchpad({
                element: '#sketchpad',
                width: this.$refs._textarea_box.offsetWidth - 20,
                height: 200,
                color:'#121212',
                penSize:2
            });
        },
        resetUserInfo(){
            
            if(!b2token){
                this.data = JSON.parse(this.$refs.formData.getAttribute('data-commenter'))
                
                this.show.info = this.data.name && this.data.user_email ? false : true
            }
        },
        undo(){
            this.sketchpad.undo()
        },
        redo(){
            this.sketchpad.redo()
        },
        color(color){
            this.sketchpadOpt.color = color
            this.sketchpad.color = color
        },
        penSize(size){
            this.sketchpadOpt.penSize = size
            this.sketchpad.penSize = size
        },
        animate(){
            this.sketchpad.animate(2)
        },
        addSmile(val){
            grin(val,document.querySelector('#textarea'))
        },
        resetmove(){
            if(this.subLocked || this.locked) return
            this.parentId = 0
            document.querySelector('#comment-form').appendChild(b2Comment.$refs.respond)
            if(this.$refs.fileInput){
                this.$refs.fileInput.value = null
            }
            this.commentData.imgUrl = ''
            this.commentData.imgId = ''
            this.show.image = false
            this.progress = 0
            b2Comment.resetWidth()
        },
        resetWidth(){
            if(!this.$refs.sketchpad) return
            setTimeout(() => {
                this.sketchpad.width = this.$refs._textarea_box.offsetWidth - 20
                this.sketchpad.reset(this.sketchpad.width)
            }, 10);
        },
        submit(){
            if(this.drawing){
                if(this.sketchpad.strokes.length == 0){
                    Qmsg['warning'](b2_global.js_text.global.sketchpad_empty,{html:true});
                    return
                }
            }

            if(this.subLocked == true || this.Locked == true) return
            this.subLocked = true

            let data = {
                'comment_post_ID':b2_global.post_id,
                'author':this.data.name,
                'email':this.data.user_email,
                'comment':this.$refs.textarea_box.value,
                'comment_parent':this.parentId,
                'img':this.commentData
            }

            if(this.drawing){
                this.updateDrawing(data)
            }else{
                this.submitAction(data)
            }

        },
        updateDrawing(data){
            this.$refs.sketchpad.toBlob((blob)=>{

                let formData = new FormData()

                formData.append('file',blob,'comment-drawing.jpg')
                formData.append("post_id", b2_global.post_id)
                formData.append("type", 'comment_drawing')

                this.$http.post(b2_rest_url+'fileUpload',formData).then(res=>{
                    console.log(res)
                    if(res.data.status == 401){
                        Qmsg['warning'](res.data.message,{html:true});
                        this.subLocked = false
                        return
                    }else{
                        data['comment'] = b2_global.js_text.global.tuya+'['+res.data.id+']'
                        data['img'] = {
                            imgUrl:res.data.url,
                            imgId:res.data.id
                        }
                        this.submitAction(data)
                    }
                }).catch(err=>{
                    Qmsg['warning'](err.response.data.message,{html:true});
                    this.subLocked = false
                    return
                })
              },'image/png');
        },
        submitAction(data){
            this.$http.post(b2_rest_url+'commentSubmit',Qs.stringify(data)).then(res=>{
                if(this.parentId){
                    b2CommentList.$el.querySelector('#comment-children-'+this.parentId).innerHTML = res.data.list
                }else{
                    b2CommentList.$refs.commentList.insertAdjacentHTML('beforeend', res.data.list)
                }
                this.$refs.textarea_box.value = ''
                this.subLocked = false
       
                this.resetmove()
                b2CommentList.listImg()
                if(b2CommentList.$refs.noneComment){
                    b2CommentList.$refs.noneComment.remove()
                }
                if(!this.$refs.sketchpad) return
                this.sketchpad.clearAll()
            }).catch(err=>{
                Qmsg['warning'](err.response.data.message[0],{html:true});
                this.subLocked = false
              
            })
        },
        deleteImage(){
            this.commentData.imgUrl = ''
            this.commentData.imgId = ''
            this.progress = 0
            this.$refs.fileInput.value = null
        },
        getFile(event){
            if(event.target.files.length <= 0) return
            if(this.locked == true) return
            this.locked = true

            this.commentData.imgUrl = ''
            this.commentData.imgId = ''

            this.progress = 0

            let file = event.target.files[0]

            this.show.image = true

            let formData = new FormData()

            formData.append('file',file,file.name)
            formData.append("post_id", b2_global.post_id)
            formData.append("type", 'comment')

            let config = {
                onUploadProgress: progressEvent=>{
                    this.progress = progressEvent.loaded / progressEvent.total * 100 | 0
                }
            }

            this.$http.post(b2_rest_url+'fileUpload',formData,config).then(res=>{
                if(res.data.status == 401){

                    Qmsg['warning'](res.data.message,{html:true});
                    this.show.image = false
                    this.progress = 0
                }else{
                    this.commentData.imgUrl = res.data.url
                    this.commentData.imgId = res.data.id
                }
                this.$refs.fileInput.value = null
                this.locked = false;
            }).catch(err=>{

                Qmsg['warning'](err.response.data.message,{html:true});
                this.locked = false
                this.show.image = false
                this.progress = 0
                this.$refs.fileInput.value = null
            })
        },
        showLogin(){
            login.show = true
        }
    },
    watch:{
        drawing(val){
            if(val){
                this.resetWidth()
            }
        },
        userData(val){
            if(val && b2token){
                this.data = val
            }
        }
    }
})

//评论列表
var b2CommentList = new Vue({
    el:'#comments',
    data:{
        selecter:'.comment-list',
        opt:{
            'post_id':b2_global.post_id
        },
        api:'getCommentList',
        options:[],
        tips:'',
        canSticky:false,
        lazyLoadInstance:''
    },
    mounted(){
        if(!this.$refs.commentPageNav) return
        this.listImg()
        this.checkVote()
        this.getTips()
        this.lazyLoadInstance = new LazyLoad({
            elements_selector: ".comment-img-box img"
        });
    },
    methods:{
        listImg(){
            let img = document.querySelectorAll('.comment-img-box img');
            if(img.length > 0){
                for (let index = 0; index < img.length; index++) {
                    b2zoom.listen(img[index]);
                }
            }
        },
        getTips(){
            this.$https.get(b2_rest_url+'getCommentTips').then(res=>{
                this.tips = res.data
            })
        },
        showSticky(){
            if(this.canSticky){
                let zd = document.querySelectorAll('.comment-zd');
                if(zd.length > 0){
                    for (let index = 0; index < zd.length; index++) {
                        zd[index].className = zd[index].className.replace(' comment-zd','')
                    }
                }
            }
        },
        sticky(comment_id){
            this.$http.post(b2_rest_url+'commentSticky','post_id='+b2_global.post_id+'&comment_id='+comment_id).then(res=>{
                this.$refs.commentPageNav.go(1,'comment',true)
                b2Comment.resetmove()
            })
        },
        vote(e,type,comment_id){

            
            if(!b2token){
                login.show = true
            }else{
                this.$http.post(b2_rest_url+'commentVote','type='+type+'&comment_id='+comment_id).then(res=>{
                    let _type;
                    if(type === 'comment_up'){
                        _type = 'comment_down'
                    }else{
                        _type = 'comment_up'
                    }

                    let next = e.parentNode.querySelector('#'+_type.replace('_','-')+'-'+comment_id);
                        next.className = next.className.replace(/ voted/g,'')

                        e.className = e.className.replace(/ voted/g,'')

                    if(res.data[type] > 0){
                        e.className += ' voted'
                    }

                    next.lastChild.innerText = parseInt(next.lastChild.innerText) + res.data[_type]
                    e.lastChild.innerText = parseInt(e.lastChild.innerText) + res.data[type]

                })
            }
        },
        checkVote(){
            
            if(!b2token) return
            let dom = document.querySelectorAll('.reply')

            if(dom.length == 0 ) return

            let ids = []

            for (let i = 0; i < dom.length; i++) {

                ids.push(dom[i].getAttribute('data-id'))
            }

            let data = {
                'ids':ids,
                'post_id':b2_global.post_id
            }

            this.$https.post(b2_rest_url+'commentVoteData',Qs.stringify(data)).then(res=>{
                if(res.data){
                    Object.keys(res.data.list).forEach(function (key) {
                        if(res.data.list[key]){
                            let dom = document.querySelector('#'+res.data.list[key].replace('_','-')+'-'+key)

                            if(dom){
                                dom.className += ' voted'
                            }
                        }
                    })
                }
                this.canSticky = res.data.can_sticky
                this.showSticky()
            })
        },
        move(comment_id){
            if(b2Comment.subLocked || b2Comment.locked) return
            b2Comment.parentId = comment_id
            document.querySelector('#comment-form-'+comment_id).appendChild(b2Comment.$refs.respond)
            
            b2Comment.resetWidth()
            
        }
    }
})

function b2ContentImageLoaded(){
    imagesLoaded( document.querySelectorAll('.entry-content'), function( instance ) {
        if (typeof window.contentFooter !== 'undefined') window.contentFooter.updateSticky()
    });
}

b2ContentImageLoaded()

var b2DownloadBox = new Vue({
    el:'#download-box',
    data:{
        list:'',
        cLogin:false
    },
    mounted(){
        if(this.$refs.downloadBox){
            
            if(!b2token){
                this.cLogin = false
            }else{
                this.cLogin = true
            }
            this.getList()
        }
    },
    methods:{
        getList(){

            let guest = b2getCookie('b2_guest_buy_'+b2_global.post_id+'_x')
                guest = JSON.parse(guest)
            let data = {
                'post_id':b2_global.post_id,
                'guest':guest
            }

            this.$http.post(b2_rest_url+'getDownloadData',Qs.stringify(data)).then(res=>{
                this.list = res.data
                if(typeof b2WidgetDownload != 'undefined' && b2WidgetDownload.$refs.wdlist && B2ClientWidth > 768){
                    b2WidgetDownload.list = res.data
                    b2WidgetDownload.$refs.gujia.style.display = 'none'
                    b2WidgetDownload.$refs.wdlist.style.display = 'block'
                    this.$refs.downloadBox.style.display = 'none'
                    document.querySelector('.single-button-download').style.display = 'none'
                }
                
                let show = false

                if(document.body.clientWidth > 720){
                    show = true
                }

                for (let i = 0; i < this.list.length; i++) {
                    this.list[i].show_role = show
                }
                
                this.$refs.gujia.style = "display:none"

                this.$nextTick(()=>{
                    b2SidebarSticky()
                
                    if (typeof window.contentFooter !== 'undefined') window.contentFooter.updateSticky()

                })
            })
        },
        login(){
            login.show = true
        },
        go(link,allow,item,index){

            if(item.current_user.lv.lv.lv === 'dark_room'){

                Qmsg['warning'](b2_global.js_text.global.dark_room_down,{html:true});
                return
            }



            if(!this.cLogin && !allow && !item.current_user.guest){
                login.show = true
            }else if(!allow){
                if(item.current_user.can.type == 'comment'){

                    Qmsg['warning'](b2_global.js_text.global.comment_down,{html:true});
                }else if(item.current_user.can.type == 'credit'){
                    this.credit(index)
                }else if(item.current_user.can.type == 'money'){
                    this.pay(index)
                }else{

                    Qmsg['warning'](b2_global.js_text.global.role_down,{html:true});
                }
                return 

            }else{
                window.open(link)
            }
        },
        pay(index){
            if(this.list[index].current_user.can.type == 'money'){
                b2DsBox.data = {
                    'title':this.list[index].name,
                    'order_price':this.list[index].current_user.can.value,
                    'order_type':'x',
                    'post_id':b2_global.post_id,
                    'order_key':index
                }
                b2DsBox.show = true;
                b2DsBox.showtype = 'normal'
            }
        },
        credit(index){
            if(!this.cLogin){
                login.show = true
            }else{
                if(this.list[index].current_user.can.type == 'credit'){
                    payCredit.data = {
                        'title':this.list[index].name,
                        'order_price':this.list[index].current_user.can.value,
                        'order_type':'x',
                        'post_id':b2_global.post_id,
                        'order_key':index
                    }
                    payCredit.show = true;
                }
            }
        }
    }
})

var b2WidgetDownload = new Vue({
    el:'.b2-widget-download',
    data:{
        list:'',
    },
    methods:{
        show(index){
            this.$set(this.list[index],'show',!this.list[index].show)
        },
        go(link,allow,item,index) {
            b2DownloadBox.go(link,allow,item,index) 
        }
    }
})

b2VideoReset()
function b2VideoReset(){
    let videos = document.querySelectorAll('.b2-player');

    if(videos.length > 0){
        var player = [];
        for (let i = 0; i < videos.length; i++) {
            let data = JSON.parse(videos[i].getAttribute('data-video'));
            setTimeout(() => {
                if(data.url){
                    player[i] = new DPlayer({
                        container: videos[i],
                        screenshot: false,
                        mutex:true,
                        hotkey:true,
                        preload:'none',
                        video: {
                            url: data.url,
                            pic: data.poster,
                        },
                        logo:data.logo != '' ? data.logo : b2_global.site_info.site_uri+'/Assets/fontend/images/xg-logo-default.png',
                        autoplay:false
                    });
                    player[i].on('play',()=>{
                        videos[i].querySelectorAll('.dplayer-video-current')[0].style="object-fit:contain"
                    })
                }
            }, 10);
            
        }
    }
}

if(typeof b2SingleMeta !== 'undefined'){
    let postData = b2SingleMeta.$watch('postData',(newVal,oldVal)=>{
        if(typeof b2ContentFooter !== 'undefined'){
            b2ContentFooter.postData = newVal
        }
        if(typeof B2ShopSingle !== 'undefined'){
            if(B2ShopSingle.$refs.shopSingle){
                B2ShopSingle.postData = newVal
            }
        }
        if(typeof documentVote !== 'undefined'){
            documentVote.postData = newVal
        }
    })
}

function b2loadScript(url, id,callback){
    var script = document.createElement ("script")
    script.type = "text/javascript";
    script.id = id;
    if (script.readyState){ //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" || script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else { //Others
        script.onload = function(){
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}

var postAuthor = new Vue({
    el:'.b2-widget-author',
    data:{
        following:false
    },
    methods:{
        followingAc(){
            
            if(!b2token){
                login.show = true
            }else{
                this.$http.post(b2_rest_url+'AuthorFollow','user_id='+b2_global.author_id).then(res=>{
                    this.following = !this.following
                }).catch(err=>{
                    Qmsg['warning'](err.response.data.message,{html:true});
                })
            }
        },
        dmsg(){
            
            if(!b2token){
                login.show = true
            }else{
                b2Dmsg.userid = b2_global.author_id
                b2Dmsg.show = true
            }
        },
    }
})