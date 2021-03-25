new Vue({
    el: '#callsDataImportApp',
    data: {
        file: null,
        clients_data: [],
        isError: false,
        errorDescription: null,
        isSuccess: false,
        inProcess: false,
    },
    computed: {
        validateFileUpload: function () {
            return this.file && this.inProcess === false;
        },
    },
    methods: {
        handleFileUpload() {
            this.resetData();

            if (this.$refs.file.files[0]['type'] === 'application/vnd.ms-excel') {
                this.file = this.$refs.file.files[0];
            } else {
                this.file = null;
                this.isError = true;
                this.errorDescription = 'Файл должен быть в формате .csv';
            }
        },
        submitFile() {
            const self = this;

            if (self.file) {
                if (self.file['type'] === 'application/vnd.ms-excel') {
                    self.resetData();
                    self.inProcess = true;

                    let formData = new FormData();

                    formData.append('file', self.file);

                    axios.post('/',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            }
                        }
                    ).then((response) => {
                        self.clients_data = Array.isArray(response.data) ? response.data : [];

                        self.file = null;
                        self.isSuccess = true;
                    }).catch((error) => {
                        self.isError = true;
                        self.errorDescription = error.response.data;
                    }).finally(() => {
                        self.inProcess = false;
                    });
                }
            }
        },
        resetData() {
            this.clients_data = [];
            this.isError = false;
            this.errorDescription = null;
            this.isSuccess = false;
        },
        toExcel() {
            let uri = 'data:application/vnd.ms-excel;base64,',
                templateData = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
                base64Conversion = function (s) {
                    return window.btoa(unescape(encodeURIComponent(s)))
                }, formatExcelData = function (s, c) {
                    return s.replace(/{(\w+)}/g, function (m, p) {
                        return c[p];
                    });
                };

            let table = document.getElementById('clients_call_statistic');
            let ctx = {
                worksheet: 'Clients call statistic',
                table: table.innerHTML,
            };

            let element = document.createElement('a');
            element.setAttribute('href', 'data:application/vnd.ms-excel;base64,' + base64Conversion(formatExcelData(templateData, ctx)));
            element.setAttribute('download', 'clients_call_statistic');
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        },
    },
});