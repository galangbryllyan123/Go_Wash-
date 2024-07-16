let TF_Lottie;
((doc, Themify,und)=> {
    'use strict';
    let observer,
        holders;
    const cacheLoaded= new Map(),
        addToScroll=obj=>{
            if(!observer){
                const scrollItems=new Set(),
                    getContainerVisibility=el=> {
                        const { top, height } = el.getBoundingClientRect(),
                            h=Themify.h,
                            current = h - top,
                            max = h + height;
                        return current / max;
                    },
                    scroll=async function(){
                        if(scrollItems.size>0){
                            for(let el of scrollItems){
                                let item=holders.get(el);
                                if(item && item.action.st==='scroll' && el.isConnected){
                                    let currentPercent=getContainerVisibility(el),
                                        vis=item.action.vis,
                                        ev=item.action.s_ev;
                                    if(currentPercent >= vis[0] && currentPercent <= vis[1]){
                                        item.player.loop=false;
                                        let paused=item.player.isPaused;
                                        if(ev==='seek') {
                                            if(!item.action.min){
                                                item.action.min=Math.min(...item.action.segment)-item.player.firstFrame;
                                                item.action.max=Math.max(...item.action.segment)-item.player.firstFrame;
                                            }
                                            let min = item.action.min,
                                                max = item.action.max;
                                            item.player.resetSegments(true);
                                            let frame = Math.round(((currentPercent - vis[0]) / (vis[1] - vis[0])) * (max-min));
                                            frame = item.action.dir === -1 ? (max - frame) : (min + frame);
                                            frame *= item.action.sp;
                                            if (frame > max) {
                                                frame = max;
                                            } else if (frame < min) {
                                                frame = min;
                                            }
                                            item.player.goToAndStop(parseInt(frame), true);
                                        }
                                        else if(ev==='loop'){
                                            item.player.loop=true;
                                            if(paused) {
                                                await item.play();
                                            }
                                        }
                                        else if(ev==='stop'){
                                            if(!paused){
                                                item.player.pause();
                                            }
                                            await item.loadNext();
                                        }else if(paused){
                                            await item.play();
                                            await item.loadNext();
                                        }
                                    }
                                    else if(currentPercent<vis[0] || currentPercent>vis[1]){
                                        let index=item.index+1;
                                        if(currentPercent<vis[0]){
                                            index-=2;
                                            if(index<0){
                                                index=0;
                                            }
                                        }
                                        holders.delete(el);
                                        scrollItems.delete(el);
                                        await item.loadNext(index);
                                    }
                                }else{
                                    holders.delete(el);
                                    scrollItems.delete(el);
                                }
                            }
                        }
                    };
                window.tfOn('scroll',scroll,{passive:true});
                observer=new IntersectionObserver( (entries, _self)=>{
                        if(entries.length>0){
                            for (let i = entries.length-1;i>-1;--i) {
                                let el=entries[i].target;
                                if(entries[i].intersectionRatio>0){
                                    let item=holders.get(el);
                                    if(item && item.action.st==='scroll'){
                                        if(!scrollItems.has(el)){
                                            scrollItems.add(el);
                                            if(!item.isScrolled){
                                                item.isScrolled=true;
                                                scroll();
                                            }
                                        }
                                    }else{
                                        _self.unobserve(el);
                                        scrollItems.delete(el);
                                        holders.delete(el);
                                    }
                                }else{
                                    scrollItems.delete(el);
                                }
                            }
                        }
                        if(holders.size===0){
                            _self.disconnect();
                            window.tfOff('scroll',scroll,{passive:true});
                            scrollItems.clear();
                            observer=holders=null;
                        }
                    }
                );
                holders=new WeakMap();
            }
            holders.set(obj.el,obj);
            observer.unobserve(obj.el);
            observer.observe(obj.el);
        };
    class LottieElement extends HTMLElement{
        async init(){
            const p=this.tfTag('template')[0],
                args=p?p.content.textContent: this.dataset.args;
            if(p){
                p.remove();
            }
            if(args){
                this.classList.add('tf_w','tf_h');
                const lottie=new TF_Lottie(this,JSON.parse(args));
                await lottie.run();
            }
        }
        connectedCallback () {
            if(!this.dataset.lazy){
                this.init();
            }
        }
        attributeChangedCallback(attribute, previousValue, currentValue){
            if(!currentValue && attribute==='data-lazy'){
                this.init();
            }
        }
        disconnectedCallback(){
            if(window.lottie){
                const players=lottie.getRegisteredAnimations(),
                    id=this.dataset.id;
                if(id){
                    for(let i=players.length-1;i>-1;--i){
                        if(players[i].animationID===id){
                            setTimeout(()=>{
                                if(!this.isConnected){
                                    players[i].destroy();
                                }
                            },100);
                            break;
                        }
                    }
                }
            }
        }
        static get observedAttributes(){
            return ['data-lazy'];
        }
    }

    TF_Lottie=class{
        constructor(el,options){
            this.el=el;
            this.actions=options.actions;
            this.loop=!!options.loop;
            this.type=options.type || 'svg';
            this.index=0;
        }
        static async getJson(path){
            let res=cacheLoaded.get(path);
            if(!res){
                res= await Themify.fetch('', null, {
                    credentials: 'omit',
                    method: 'GET',
                    mode: 'cors'
                }, TF_Lottie.getJsonUrl(path));
                cacheLoaded.set(path,JSON.stringify(res));
            }else{
                res= JSON.parse(res);
            }
            return res;
        }
        static getJsonUrl(url){
            if(!url.includes('.json')){
                let cdn=Math.floor(Math.random() * 10 + 1),
                    fullPath='https://assets'+cdn+'.lottiefiles.com';
                if(url[0]!=='/'){
                    fullPath+='/packages/';
                }
                url=encodeURI(fullPath+url+'.json');
            }
            return url;
        }
        isLive(){
            if(!this.el || !this.el.isConnected || !this.player || this.player._cbs===null){
                this.destroy();
                return false;
            }
            return true;
        }
        destroy(cache){
            if(this.player){
                this.player.destroy();
            }
            if(cache && this.actions){
                for(let i=this.actions.length-1;i>-1;--i){
                    if(this.actions[i].path){
                        cacheLoaded.delete(this.actions[i].path);
                    }
                }
            }
            this.el=this.player=this.actions=this.action=null;
        }
        loadChain(){
            const prms=[],
                max=2;
            for(let i=this.index,j=0,n=this.actions.length;i<n;++i){
                let item=this.actions[i],
                    allow=i<1;
                if(item.data){
                    prms.push(item.data);
                }
                else if(item.path){
                    if(allow===false){
                        ++j;
                        if(j>max){
                            break;
                        }
                        else if(j===1){
                            allow=true;
                        }
                    }
                    if(allow===true){
                        prms.push(TF_Lottie.getJson(item.path));
                    }
                }
            }
            return prms;
        }
        run(){
            return new Promise(async resolve=>{
                if(this.actions.length===0){
                    resolve();
                    return;
                }
                const data=await Promise.all([Themify.loadJs('https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.0/lottie.min.js',!!window.lottie),...this.loadChain()]),
                    loaded=()=>{
                        if(this.player){
                            this.player.removeEventListener('DOMLoaded',loaded);
                        }
                        this.init();
                        if(this.el) {
                            this.el.style.height = '';
                            this.el.dataset.id = this.player.animationID;
                        }
                        resolve();
                    };
                if(this.index>0 && data[1].w>0 && data[1].h>0){
                    this.el.style.height=(parseFloat(this.el.getBoundingClientRect().width/parseFloat(data[1].w/data[1].h)))+'px';
                }
                if(!this.el){
                    resolve();
                    return;
                }
                let root=this.el.shadowRoot;
                if(!root){
                    root=doc.createElement('div');
                    this.el.attachShadow({ mode: 'open' }).appendChild(root);
                    root.style.width=root.style.height='100%';
                    root.style.display='flex';
                    lottie.setQuality('medium');
                    //  lottie.useWebWorker (true);
                }else{
                    root=root.firstChild;
                    if(this.player){
                        this.player.destroy();
                    }
                }
                try{
                    this.player=lottie.loadAnimation({
                        container:root,
                        animationData: data[1],
                        renderer:this.type,
                        loop: false,
                        autoplay: false,
                        rendererSettings: {
                            progressiveLoad: true
                        }
                    });
                    this.player.addEventListener('DOMLoaded',loaded);
                }catch (e){
                    console.log(e,this.action);
                }
            });
        }
        async init(){
            if(this.isLive()){
                this.action=this.actions[this.index];
                const action=this.action,
                    player=this.player;
                let state=action.st;
                player.resetSegments(true);
                if(!action.segment){
                    if(!state){
                        state=action.st='autoplay';
                    }
                    action.dir=parseInt(action.dir)<0?-1:1;
                    action.sp=action.sp>0?parseFloat(action.sp):1;
                    let segment=[0,player.getDuration(true)];
                    if(action.fid || action.seg){
                        let marker;
                        if(action.fid){
                            marker=player.getMarkerData(action.fid.trim());
                            if(marker){
                                segment[0]=marker.time;
                                segment[1]=marker.time+marker.duration;
                            }
                        }
                        if(action.seg){
                            marker=action.seg.split(',');
                            for(let i=marker.length-1;i>-1;--i){
                                marker[i]=parseInt(marker[i].trim());
                                if(marker[i]<segment[0] || marker[i]>segment[1]){
                                    marker[i]=i===0?segment[0]:segment[1];
                                }
                            }
                        }
                        segment=marker;
                    }
                    if(action.dir===-1){
                        segment.reverse();
                    }
                    if(state==='scroll'){
                        action.count=0;
                        action.tr='Autoplay';
                        if(action.vis){
                            action.vis=action.vis.split(',');
                            action.vis[0]=action.vis[0]/100;
                            action.vis[1]=action.vis[1]/100;
                        }else{
                            action.vis=[0,1];
                        }
                        action.min=Math.min(...segment);
                        action.max=Math.max(...segment);
                    }
                    else if(state==='hold' || state==='pausehold'){
                        action.count=0;
                        action.tr='Autoplay';
                    }else{
                        if(action.sp<1 && state==='seek'){
                            action.sp=1;
                        }
                        action.tr=action.tr?(action.tr.charAt(0).toUpperCase()+  action.tr.slice(1)):'Autoplay';
                        action.count=action.count>0?parseInt(action.count):1;
                    }
                    segment[0]+=player.firstFrame;
                    segment[1]+=player.firstFrame;
                    action.segment=segment;
                }
                player.loop=false;
                player.setSpeed(action.sp);
                player.goToAndStop(action.segment[0], true);
                try{
                    const cl=this.el.classList;
                    if(this.index>0){
                        cl.remove('tf_lottie_'+this.actions[this.index-1].st);
                    }
                    cl.add('tf_lottie_'+state);
                    if(state!=='none'){
                        await this[state]();
                    }
                    if(state!=='scroll'){
                        if(action.tr!=='Autoplay'){
                            player.resetSegments(true);
                            player.goToAndStop(action.segment[0], true);
                            await this['tr'+action.tr]();
                        }
                        await this.loadNext();
                    }
                }catch (e){
                    console.log(e);
                }
            }
        }
        async autoplay(){
            for(let i=this.action.count-1;i>-1;--i){
                await this.play();
            }
        }
        click(type,max){
            return new Promise(resolve=>{
                let count=0,
                    isWorking=false;
                if(max===und){
                    max=this.action.count;
                }
                const ev=type || Themify.click,
                    callback=async ()=>{
                        if(!isWorking){
                            isWorking=true;
                            ++count;
                            if(count<=max){
                                try {
                                    await this.play();
                                }
                                catch(e){

                                }
                            }
                            if(count>=max){
                                this.el.tfOff(ev,callback,{passive:true});
                                count=null;
                                resolve();
                            }
                            isWorking=null;
                        }
                    };
                this.el.tfOn(ev,callback,{passive:true});
                if(type!==und && this.player.isPaused && this.el.matches(':hover')){
                    callback();
                }
            });
        }
        hover(){
            return this.click('pointerenter');
        }
        hold(type){
            return new Promise(resolve=>{
                let segment=this.action.segment.slice(),
                    first=segment[0]-this.player.firstFrame,
                    rendered=this.player.renderer;
                this.player.goToAndStop(first, true);
                const leave=async ()=>{
                        this.player.pause();
                        this.player.trigger('reject');
                        if(type!=='pausehold'){
                            try{
                                const reverse=[rendered.renderedFrame,first+this.player.firstFrame];
                                this.player.resetSegments(true);
                                await this.play(reverse);
                            }
                            catch(e){

                            }
                        }
                    },
                    enter=async ()=>{
                        try{
                            this.player.trigger('reject');
                            this.el.tfOff('pointerleave',leave,{passive:true,once:true})
                                .tfOn('pointerleave',leave,{passive:true,once:true});
                            segment[0]=rendered.renderedFrame;
                            await this.play(segment);
                            this.el.tfOff('pointerenter',enter,{passive:true})
                                .tfOff('pointerleave',leave,{passive:true,once:true});
                            segment=first=rendered=null;
                            resolve();
                        }
                        catch(e){

                        }
                    };
                this.el.tfOn('pointerenter',enter,{passive:true});
                if(this.player.isPaused && this.el.matches(':hover')){
                    enter();
                }
            });
        }
        pausehold(){
            return this.hold('pausehold');
        }
        scroll(){
            addToScroll(this);
        }
        seek(){
            return new Promise(resolve=>{
                const enter=()=>{
                    let box=this.el.getBoundingClientRect(),
                        w=box.width,
                        h=box.height,
                        max=Math.max(...this.action.segment)-this.player.firstFrame,
                        min=Math.min(...this.action.segment)-this.player.firstFrame,
                        frameLength=max-min,
                        isReverse=this.action.dir===-1,
                        req,
                        prevFrame=0;
                    const leave=e=>{
                            this.el.tfOff('pointermove',move,{passive:true})
                                .tfOff('pointerleave',leave,{passive:true,once:true});
                            if(req){
                                cancelAnimationFrame(req);
                            }
                            if(e){
                                move(e);
                            }
                            requestAnimationFrame(()=> {
                                w=h=frameLength=prevFrame=req=max=min=isReverse=null;
                            });
                        },
                        move=e=>{
                            req=requestAnimationFrame(()=> {
                                if(min!==null){
                                    let offsetX=e.offsetX<0?0:e.offsetX,
                                        offsetY=e.offsetY<0?0:e.offsetY,
                                        frame = Math.ceil(parseInt(parseFloat(offsetX / w)* frameLength) *this.action.sp);
                                    frame=isReverse?(max-frame):(min+frame);
                                    if(frame>max){
                                        frame=max;
                                    }else if(frame<min){
                                        frame=min;
                                    }
                                    if(prevFrame!==frame){
                                        prevFrame=frame;
                                        this.player.goToAndStop(frame, true);
                                    }
                                    if ((!isReverse && frame >=(max-1)) || (isReverse && frame<=(min+1))) {
                                        this.el.tfOff('pointerenter',enter,{passive:true});
                                        leave();
                                        resolve();
                                    }
                                }
                            });
                        };
                    this.el.tfOn('pointerleave',leave,{passive:true,once:true})
                        .tfOn('pointermove',move,{passive:true});

                };
                this.el.tfOn('pointerenter',enter,{passive:true});
                if(this.player.isPaused && this.el.matches(':hover')){
                    enter();
                }
            });
        }
        basket(){
            return new Promise(resolve => {
                jQuery('body').one('added_to_cart', async e=>{
                    try {
                        for(let i=this.action.count-1;i>-1;--i){
                            await this.play();
                        }
                    }catch (e){

                    }
                    resolve();
                });
            });
        }
        trHover(){
            return this.hover('hover',this.actions[this.index].tr_count || 1);
        }
        trClick(){
            return this.click(und,this.actions[this.index].tr_count || 1);
        }
        trSeek(){
            return this.seek();
        }
        loadNext(index){
            return new Promise(resolve=> {
                if (index === und) {
                    index = this.index + 1;
                }
                if (index >= this.actions.length) {
                    if (this.loop === true) {
                        index = 0;
                    } else {
                        return;
                    }
                }
                const prevIndex = this.index,
                    delay = this.action.del > 0 ? parseFloat(this.action.del) * 1000 : 0,
                    __calback = async () => {
                        this.index = index;
                        if(this.actions[index].path === this.actions[prevIndex].path){
                            await this.init();
                        }else{
                            await this.run();
                        }
                        resolve();
                    };
                if(delay>0){
                    setTimeout(()=>{
                        __calback();
                    },delay);
                }
                else{
                    __calback();
                }
            });
        }
        play(segment){
            return new Promise((resolve,reject)=>{
                requestAnimationFrame(()=>{
                    const checkPlayer=()=>{
                        if(!this.isLive()){
                            reject();
                            return false;
                        }
                        return true;
                    };
                    if(checkPlayer()){
                        const ev=this.player.loop?'loopComplete':'complete',
                            complete=e=>{
                                if(checkPlayer()) {
                                    this.player.removeEventListener(ev, complete);
                                    this.player.removeEventListener('reject', complete);
                                }
                                if(e && e.type===ev){
                                    resolve();
                                }else{
                                    reject('rejected');
                                }
                            };
                        if(!segment){
                            segment=this.action.segment;
                        }
                        if(segment[0]===segment[1]){
                            this.player.goToAndStop(segment[0], true);
                            resolve();
                        }else{
                            this.player.addEventListener(ev,complete);
                            this.player.addEventListener('reject',complete);
                            this.player.playSegments(segment,true);
                        }
                    }
                });
            });
        }
    };

    customElements.define('tf-lottie', LottieElement);

})(document, Themify,undefined);