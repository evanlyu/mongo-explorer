
/** a.123.111 indexes **/
db.getCollection("a.123.111").ensureIndex({"_id":NumberInt(1)},{});

/** a.1 indexes **/
db.getCollection("a.1").ensureIndex({"_id":NumberInt(1)},{});

/** a.system.indexes records **/
db.getCollection("a.system.indexes").insert({"v":NumberInt(1),"key":{"_id":NumberInt(1)},"name":_id_,"ns":a.1});
db.getCollection("a.system.indexes").insert({"v":NumberInt(1),"key":{"_id":NumberInt(1)},"name":_id_,"ns":a.123.111});

/** a.123.111 records **/

/** a.1 records **/
db.getCollection("a.1").insert({"_id":ObjectId("52a9f01e65c2db50e60041a8"),"1":"1"});
