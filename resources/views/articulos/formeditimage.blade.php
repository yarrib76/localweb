<div class="form-group">
    <div class="col-sm-6">
        <label>Foto</label>
        <table>
            <tr>
                <td>
                    <input type="file" class="form-control" id="image_name_1" name="image_name_1"
                           onchange="PreviewImage1();">
                </td>
                <td>
                @if(!empty($articulo['ImageName']))
                        <img style="display: inline;" id="uploadPreview1"
                             src="/imagenes/articulos/{{{$articulo['ImageName']}}}" alt="" height="52" width="52"/>
                        <input type="hidden" name="image_name_1" id="input_image_name_1" value={{{$articulo['ImageName']}}} >
                        <button type="button" name="eliminar" value="eliminar" onClick="borrar(1)" />
                    @else
                        <img style="display: none;" id="uploadPreview1" src="#" alt="" height="52" width="52"/>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

@section('extra-javascript')
    <script type="text/javascript">
        function PreviewImage1() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("image_name_1").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("uploadPreview1").src = oFREvent.target.result;
                document.getElementById("uploadPreview1").style.display = "inline";
            };
        };

        function PreviewImage2() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("image_name_2").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("uploadPreview2").src = oFREvent.target.result;
                document.getElementById("uploadPreview2").style.display = "inline";

            };
        };
        function PreviewImage3() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("image_name_3").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("uploadPreview3").src = oFREvent.target.result;
                document.getElementById("uploadPreview3").style.display = "inline";

            };
        };
        function borrar(imagen){
            if(imagen == 1){
                document.getElementById("input_image_name_1").value = "";
                document.getElementById("uploadPreview1").src = ""
                document.getElementById("uploadPreview1").style.display = "none";
            }
            if(imagen == 2){
                document.getElementById("input_image_name_2").value = "";
                document.getElementById("uploadPreview2").src = ""
                document.getElementById("uploadPreview2").style.display = "none";
            }
            if(imagen == 3){
                document.getElementById("input_image_name_3").value = "";
                document.getElementById("uploadPreview3").src = ""
                document.getElementById("uploadPreview3").style.display = "none";
            }
        }
    </script>
@stop



