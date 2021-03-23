
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
const $ = require('jquery');
require('jquery-ui/ui/widgets/autocomplete');
require('bootstrap');
require('bootstrap/scss/bootstrap.scss');
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    if ($('div.table-block').length > 0) {
        let table = $('div.table-block').makeTable();
        table.getList();
    }
    // initTable();
    // if (typeof  formName !== 'undefined') {
    //     initForm(formName, urlPath, columns);
    // }
});

$.fn.makeTable = function () {
    let self = this;
    self.id = $(self).attr('id');

    self.formId = $(self).data('form-id');
    $(self).removeAttr('data-form-id');
    self.form = $("#" + self.formId);
    self.errorsPanel = $(self).find("#" + self.id + "-errors");
    self.searchButton = $("#" + self.formId + "_submit");
    self.resetButton = $("#" + self.formId + "_reset");
    self.action = self.form.attr('action');
    self.method = self.form.attr('method');

    self.table = $(self).find("#" + self.id + "-table");
    self.tBody = $(self.table).find('tbody');

    self.columns = {};
    self.sortBy = null;
    self.sortOrder = null;
    $(this).find('th').each( function (key) {
        let column = $(this);

        let columnData = column.data('column');

        self.columns[key] = columnData;
        column.removeAttr("data-column");
        column.removeData("column");
        column.data('id', columnData.name);

        if (typeof  columnData.sortable !== 'undefined' && columnData.sortable === true) {
            column.addClass('sortable-column');
            column.sortBy = columnData.name;
            column.sortOrder = 'desc';
            column.click( function () {
                if (self.sortBy !== column.sortBy) {
                    self.sortBy = column.sortBy;
                    column.sortOrder = 'DESC';
                } else if (column.sortOrder === 'ASC') {
                    column.sortOrder = 'DESC';
                } else {
                    column.sortOrder = 'ASC';
                }
                self.sortOrder = column.sortOrder;
                self.getList();
            });
        }
    });

    self.tableRows = 0;

    self.parseString = function (stringToParse, itemData, rowData) {
        let find = "@@.*?@@";
        let reg = new RegExp(find, 'gm');

        return stringToParse.replace(reg, function (sub) {
            let subDataKey = sub.slice(2, -2);
            let splitSub = subDataKey.split('|');
            let rowDataKey = splitSub[0];

            if (typeof rowData[rowDataKey] !== 'undefined') {
                let rowDataValue = rowData[rowDataKey];
                if (splitSub.includes("m")) {
                    rowDataValue = itemData.map[rowDataValue];
                }
                if (itemData.type === 'date') {
                    let format = "";
                    $.each(splitSub, function () {
                        let splitPart = this;
                        if (splitPart.indexOf("f_") === 0) {
                            format = splitPart.slice(2);
                        }
                    });
                    rowDataValue = rowDataValue.date;
                    if (format.length > 0) {
                        rowDataValue = self.formatDate(rowDataValue, format);
                    }
                }

                return rowDataValue;
            }

            return 'undefined';
        });
    };

    /**
     * @param records
     */
    self.setRows = function (records) {
        self.tBody.empty();
        $.each(records, function (key, value) {
            let row = value;
            let tr = $('<tr>');
            $.each(self.columns, function () {
                let column = this;
                let cellValue = '';
                let td = $('<td>');

                let div = $('<div>');
                if (typeof column.class !== 'undefined') {
                    div.addClass(column.class);
                }
                $.each(column.items, function () {
                    let item = this;
                    let itemLabel = self.parseString(item.label, item, row);

                    if (item.type === 'link') {
                        let hrfContent = self.parseString(item.url, item, row);
                        let link = $('<a>').prop('href', hrfContent).attr("target","_blank").html(itemLabel);
                        if (typeof item.class !== 'undefined') {
                            link.addClass(item.class);
                        }

                        div.append('&nbsp;', link);
                    } else {
                        div.append('&nbsp;', itemLabel);
                    }

                    cellValue = div;
                });

                td.html(cellValue);
                tr.append(td);
            });
            self.tBody.append(tr);
        });
    };

    self.pageSize = null;
    self.totalRecords = null;
    self.page = null;
    self.offset = null;
    self.pageSizeSpans = $(self).find("span.page-size");
    self.dataPages = $(self).find("li.data-pages");
    self.paginationInfoSpan = $(self).find("span.pagination-info");
    self.paginationItemsUl = $(self).find("ul.pagination-items");

    self.dataPages.each(function () {
        let pageSize = $(this).data('pages');
        $(this).removeAttr('data-pages');
        $(this).data('pages', pageSize);
    });

    self.dataPages.click(function () {
        let pageSize = $(this).data('pages');
        if (self.pageSize !== pageSize) {
            self.pageSize = pageSize;
            self.setPaginationPages();
            self.getList();
        }
    });

    /**
     * @param self.paginationItems
     * @param self.paginationItems.first
     * @param self.paginationItems.previous
     * @param self.paginationItems.pages
     * @param self.paginationItems.next
     * @param self.paginationItems.last
     */
    self.paginationItems = $(self).data('pagination-items');
    console.log($(self).data());
    console.log(self.paginationItems);
    $(self).removeAttr('data-pagination-items');

    /**
     * @param paginationData
     * @param paginationData.page_size
     * @param paginationData.page
     * @param paginationData.total_records
     */
    self.setPagination = function (paginationData) {

        if (typeof paginationData.page_size === 'undefined' || typeof paginationData.page === 'undefined') {
            return;
        }

        self.page = parseInt(paginationData.page);
        self.pageSize = parseInt(paginationData.page_size);

        if (self.page < 1) {
            return;
        }

        self.offset = (self.page - 1) * self.pageSize;

        if (typeof paginationData.total_records !== 'undefined') {
            self.totalRecords = parseInt(paginationData.total_records);
        } else if (self.tableRows < self.pageSize) {
            self.totalRecords = self.offset + self.tableRows;
        } else {
            self.totalRecords = null;
        }


        self.setPaginationPages();
        self.setPaginationInfo();
        self.setPaginationItems();
    };

    self.setPaginationPages = function () {
        if (self.pageSize !== null) {
            self.pageSizeSpans.text(self.pageSize);
            self.dataPages.each(function () {
                if ($(this).data('pages') === self.pageSize) {
                    if (!$(this).hasClass('active')) {
                        $(this).addClass('active');
                    }
                } else {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    }
                }
            });
        }
    };

    self.setPaginationInfo = function () {
        let info = '';

        let itemFrom = self.offset + 1;
        let itemTo = self.offset + self.tableRows;

        if (self.totalRecords === 0) {
            info = "0 rows";
        } else if (itemFrom !== itemTo) {
            info = "Showing " + itemFrom + " to " + itemTo;
        } else {
            info = "Showing " + itemFrom;
        }

        if (self.totalRecords > 0) {
            info = info + " of " + self.totalRecords + " rows";
        } else {
            info = info + " rows";
        }

        self.paginationInfoSpan.text(info);
    };

    self.setPaginationItems = function () {
        self.paginationItemsUl.empty();
        let disabled = false;

        if (self.totalRecords > 0 && self.pageSize >= self.totalRecords ) {
            return;
        }

        let pages = null;
        if (self.totalRecords > 0) {
            pages = Math.ceil(self.totalRecords/self.pageSize);
        }

        if (self.paginationItems.first === true) {
            disabled = self.page < 2;
            self.addPaginationItem('first page', 1, '‹‹', false, disabled);
        }
        if (self.paginationItems.previous === true) {
            disabled = self.page < 2;
            if (disabled) {
                self.addPaginationItem('previous page', 1, '‹', false , disabled);
            } else {
                self.addPaginationItem('previous page', self.page - 1, '‹', false , disabled);
            }
        }

        if (pages > 0 && self.paginationItems.pages) {
            let showPages = self.paginationItems.pages;
            if (pages < self.paginationItems.pages) {
                showPages = pages;
            }
            let minPage = self.page;
            let maxPage = self.page;
            showPages--;
            while (showPages > 0) {
                if (maxPage + 1 <= pages) {
                    maxPage++;
                    showPages--;
                    if (showPages < 1) {
                        break;
                    }
                }
                if (minPage - 1 > 0) {
                    minPage--;
                    showPages--;
                }
            }
            for (let i = minPage; i < maxPage + 1; i++) {
                let active = false;
                if (i === self.page) {
                    active = true;
                }
                self.addPaginationItem('to page ' + i, i, i, active , false);
            }
        }

        if (self.paginationItems.next === true) {
            disabled = self.tableRows !== self.pageSize;
            let nextPage = self.page;
            if (!disabled) {
                nextPage++;
            }
            self.addPaginationItem('next page', nextPage, '›', false, disabled);
        }
        if (pages > 0 && self.paginationItems.last === true) {
            disabled = self.page === pages;
            self.addPaginationItem('last page', pages, '››', false, disabled);
        }
    };

    self.addPaginationItem = function(label, page, text, active, disabled) {
        let pageItem = $("<li>").addClass('page-item').appendTo(self.paginationItemsUl);
        if (active) {
            pageItem.addClass('active');
        }
        if (disabled) {
            pageItem.addClass('disabled');
        }
        let link = $("<a>").addClass('page-link').attr({
            'aria-label': label,
            'href':"javascript:void(0)"
        }).data('page', page).text(text).click(function () {
            self.page = page;
            self.getList();
        }).appendTo(pageItem);

    };

    self.searchButton.click(function (e) {
        e.preventDefault();
        self.getList();
    });

    self.resetButton.click(function (e) {
        e.preventDefault();
        self.form[0].reset();
        self.sortBy = null;
        self.sortOrder = null;
        self.getList();
    });

    self.form.submit(function (e) {
        e.preventDefault();
        self.getList();
    });


    self.getList = function () {
        let dt = new FormData(self.form[0]);
        if (self.sortBy !== null) {
            dt.append(self.formId + '[sort_field]', self.sortBy);
        }
        if (self.sortOrder !== null) {
            dt.append(self.formId + '[sort_direction]', self.sortOrder);
        }
        if (self.pageSize !== null) {
            dt.append(self.formId + '[page_size]', self.pageSize);
        }
        if (self.page !== null) {
            dt.append(self.formId + '[page]', self.page);
        }
        self.errorsPanel.empty();
        self.errorsPanel.hide();
        $.ajax({
            url: self.action,
            type: self.method,
            data: dt,
            contentType: false,
            processData: false,
            success: function (response) {
                // parse response
                response = JSON.parse(response);
                // check response success
                if (response.success) {
                    if (response.records) {
                        self.tableRows = Object.keys(response.records).length;
                        self.setRows(response.records);
                    }
                    /**
                     * @param response.parameters
                     */
                    if (response.parameters) {
                        $.each(response.parameters, function (key, value) {
                            if (key === self.formId) {
                                $.each(value, function (parameterKey, parameterValue) {
                                    let parameter =  self.form.find("#" + self.formId + '_' + parameterKey);
                                    parameter.value = parameterValue;
                                });

                                self.setPagination(value);
                            }
                        });
                    }
                } else {
                    if (response.errors) {
                        self.errorsPanel.show();
                        $.each(response.errors, function () {
                            let errorRow = $("<div>").addClass('row').html(this);
                            errorRow.appendTo(self.errorsPanel);
                        });
                    }
                }
            },
            error: function (response) {
            }
        });
    };

    self.formatDate = function (date, format) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        let str = format.replace("Y", year);
        str = str.replace("m", month);
        str = str.replace("d", day);

        return str;
    };

    return self;
};
