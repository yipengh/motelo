<input type="hidden" id="instance_url" value="<?= $data['url'] ?>">
<div class="row">
    <div class="col-md-12">
        <a href="<?= $app->urlFor('list', array('type' => 'instance')) ?>" 
            class="btn btn-success" role="button">
            &larr;Back
        </a>
    </div>
</div>
<div class="row">
    <div id="test_case" class="col-md-4">
        <h3>Tested graph:</h3>
        <div class="graph_wrapper">
            <img class="loading_img" src="/img/loading.gif">
        </div>
    </div>
    <div class="holder col-md-4"></div>
    <div id="instance_content" class="col-md-4">
        <h3>Instance file content:</h3>
        <div id="content_wrapper">
            <textarea class="form-control" rows="22" readonly autofocus="on"></textarea>
        </div>
    </div>
</div>
