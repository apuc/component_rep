<div class="h1">{$h1}</div>
<a href="/admin/modules/create" class="btn btn-dark">Создать</a>
<a href="/create-manifest" class="btn btn-dark">Обновить манифест</a>
{core\GridView::widget()->setParams($model, $options)->run()}