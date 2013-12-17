
/** a.1 indexes **/
db.getCollection("a.1").ensureIndex({"_id":NumberInt(1)},{});

/** a.1 records **/
db.getCollection("a.1").insert({"_id":ObjectId("52a9f01e65c2db50e60041a8"),"1":"1"});
