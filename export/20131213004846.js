<center><a href='http://127.0.0.1/phpMongoEpr/export/20131213004846.js' target='_new'>Download!</a></center><br>
/** 123.111 indexes **/
db.getCollection("123.111").ensureIndex({"_id":NumberInt(1)},{});

/** 123.system.indexes records **/
db.getCollection("123.system.indexes").insert({"v":NumberInt(1),"key":{"_id":NumberInt(1)},"name":_id_,"ns":"123.111"});

/** 123.111 records **/
