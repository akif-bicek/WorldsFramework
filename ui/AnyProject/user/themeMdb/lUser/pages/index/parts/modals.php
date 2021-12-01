<div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-mdb-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form class="detail-area" data-table="deneme">
                    <!-- client side
                     3 çeşit form
                     1.form serialize js
                     2.normal
                     3.full control js (herhangi bir divin altıda olabilir)

                     üçüsününde ortak özelliği tablodaki kolonlara karşılık bir key ve value göndereleri
                     server side
                     sunucu tarafında tek bir metod olacak oda yukardaki üç çeşit veriyi alıp işleme göre veritabanına kaydetmek olacaktır
 -->
                    <div class="row mb-4">
                        <div class="col d-flex justify-content-center">
                            <img
                                src="{resim}"
                                class="card-img-top"
                                alt="{metin/0-30}"
                            />
                        </div>
                    </div>
                    <div class="form-outline mb-4">
                        <textarea class="form-control" id="form4Example3" rows="4">{metin}</textarea>
                        <label class="form-label" for="form1Example1">Metin</label>
                    </div>
                    <div class="form-outline mb-4">
                        <input type="number" id="form1Example2" class="form-control" value="{sayi}" />
                        <label class="form-label" for="form1Example2">Sayı</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                </form>
                <div class="detail-area" data-table="deneme">
                    <label>Image</label>
                    <img src="{resim}" alt="{metin/0-30}" class="card-img-top">
                    <label>Text</label>
                    <p>{metin}</p>
                    <label>Number</label>
                    <span>{sayi}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div
    class="modal fade"
    id="exampleModal2"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-mdb-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div class="detail-area" data-table="deneme#second">
                    <label>Image</label>
                    <img src="{resim}" alt="{metin/0-30}" class="card-img-top">
                    <label>Text</label>
                    <p>{metin}</p>
                    <label>Number</label>
                    <span>{sayi}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>