<?php
require('../../config.php');

$PAGE->set_pagelayout('main');

$PAGE->requires->js(new moodle_url('https://code.jquery.com/jquery-3.3.1.js'), true);

$PAGE->requires->css(new moodle_url('https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'));
?>

<?php (is_siteadmin()) ? "" : redirect('/') ?>

<?= $OUTPUT->header(); ?>
<?= $OUTPUT->heading('<h1 class="text-center">ОБРАТНАЯ СВЯЗЬ</h1>'); ?>

    <table class="table table-bordered table-striped" id="feedback">
        <thead>
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th>EMAIL</th>
            <th>Должность</th>
            <th>Сообщение</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th><input type="text" class="form-control" placeholder="id"></th>
            <th><input type="text" class="form-control" placeholder="ФИО"></th>
            <th><input type="text" class="form-control" placeholder="EMAIL"></th>
            <th><input type="text" class="form-control" placeholder="Должность"></th>
            <th><input type="text" class="form-control" placeholder="Сообщение"></th>
            <th><input type="date" class="form-control" placeholder="Дата"></th>
        </tr>
        </tfoot>
    </table>

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'fio', name: 'fio'},
            {data: 'email', name: 'email'},
            {data: 'post', name: 'post'},
            {data: 'message', name: 'message'},
            {data: 'created_at', name: 'created_at'}
        ];
        $('#feedback').DataTable({
            searching: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json"
            },
            processing: true,
            serverSide: true,
            ajax: "admin.php",
            aLengthMenu: [
                [5, 10, 25, 50, 75, 100],
                [5, 10, 25, 50, 75, 100]
            ],
            columns: columns,
            initComplete: function () {
                var timerID;
                this.api().columns().every(function (item) {
                    var self = this;
                    $(this.footer()).find('input,select').on('keyup change', function () {
                        clearTimeout(timerID);
                        timerID = setTimeout($.proxy(function () {
                            if (self.search() !== this.value) {
                                self.search(this.value).draw();
                            }
                        }, this), 500);
                    });
                });
            }
        });
    </script>

<?= $OUTPUT->footer() ?>