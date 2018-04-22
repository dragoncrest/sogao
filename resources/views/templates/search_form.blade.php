            <form id="sear-i" class="sear-form clear" method="post" onsubmit="return $.CheckSearch();"  action="/search">
                {{ csrf_field() }}
                <!--<input id="sear-inp" class="sear-inp" type="text" name="search" value="tìm kiếm....." />-->
                <span class="sear-legend">Chuyên mục: </span>
                <select class="sear-select" name="cat">
                    <option value="0">tất cả</option>
                @foreach($cates as $cat)
                    @php
                        $select = "";
                        if (!empty($currentCat) && ($cat->id == $currentCat->id)) {
                            $select = "selected";
                        }
                    @endphp
                    <option {!! $select !!} value="{!! $cat->id !!}">{!! $cat->title !!}</option>
                @endforeach
                </select>
                <input class="sear-but" type="submit" value="" />
            </form><!-- end search form -->