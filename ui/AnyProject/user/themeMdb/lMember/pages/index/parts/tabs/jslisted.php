<div class="row data-layer" data-table="deneme">
    <div class="col-3">
        <div class="card">
            <img
                src="{resim}"
                class="card-img-top"
                alt="{metin/0-30}"
            />
            <div class="card-body">
                <h5 class="card-title">{metin/0-30}</h5>
                <p class="card-text">
                    {metin/0-80}...
                </p>
                <button type="button" data-uniqe="{id}" data-event="click" data-type="notReload" data-table="deneme" class="btn btn-primary detail">classic(afterUI)</button>
                <button type="button" data-params="'{id}','notReload','click','deneme'" class="btn btn-primary detail" data-mdb-toggle="modal" data-mdb-target="#exampleModal">a params(NOT afterUI)</button>
                <button type="button" data-uniqe="{id}" data-event="dblclick" data-type="notReload" data-table="deneme" class="btn btn-primary detail">various events (afterUI)</button>
                <button type="button" data-params="'{id}','notReload','click','deneme#second'" class="btn btn-primary detail">a table another event (afterUI)</button>
                <br />
                <button type="button" data-params="'{id}','reload','click'" class="btn btn-primary detail">Page Reload(this bad alternative a href)</button>
                <a href="{id}"  class="btn btn-primary detail">Normal Href</a> <!--get metodu kullanılmıyacaktır front end geliştirmesinde bu kısım toparlanacaktır-->
            </div>
        </div>
    </div>
</div>
<!--
Mouse Events	Keyboard Events	    Form Events	    Document/Window Events
click	        keypress	        submit	        load
dblclick	    keydown	            change	        resize
mouseenter	    keyup	            focus	        scroll
mouseleave	 	blur	            unload
-->