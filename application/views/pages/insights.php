<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Insights</h1>
</div>
<form class="form-inline mr-2" id="form-insights" action="<?= base_url('ajax/ajax-insights') ?>" method="POST">
    <input type="text" name="dates" class="form-control my-1 mr-sm-2">
    <button type="submit" class="btn btn-outline-secondary btn-sm my-1 mr-sm-2">Go</button>
</form>
<div class="row">
    <br />
    <div class="col text-center">
        <!-- <h2>Overview</h2> -->
        <!-- <p>counter to count up to a target number</p> -->
    </div>
</div>

<div class="row" id="graphs">
    <div class="col-12 mb-5 chart-wrapper">
        <div id="bubblechart-container">
            <!-- bubble chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper">
        <div id="barchart-container-region">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-container-latrine-coverage">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-container-sanitation-category">
            <!-- bar chart area container -->
        </div>
    </div>
    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-duration-of-water-collection">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-water-treatment">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-family-savings">
            <!-- bar chart area container -->
        </div>
    </div>
</div>