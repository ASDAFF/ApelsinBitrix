function ContentToColumns(contentArchiveBlockId,columnsBlockId) {
    if($.isArray(columnsBlockId)) {        
        jQuery.each($(contentArchiveBlockId).children(), function() {
            var columnName = columnsBlockId[0];
            var columnHeight = $(columnsBlockId[0]).height();

            jQuery.each(columnsBlockId, function() {
                var thisBlockID = this.toString();
                if($(thisBlockID).height() < columnHeight) {
                    columnHeight = $(thisBlockID).height();
                    columnName = thisBlockID;
                }
            });
            $(columnName).append(this);
        });
    } else {
        alert('columnsBlockId is not array');
    }
}
