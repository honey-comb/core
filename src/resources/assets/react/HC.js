HC = {};

function requireAll(r) { r.keys().forEach(r); }

requireAll(require.context('./shared', true, /\.js$/));
requireAll(require.context('./form', true, /\.js$/));
requireAll(require.context('./admin-list', true, /\.js$/));
