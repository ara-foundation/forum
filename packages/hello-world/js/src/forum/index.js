import {extend, override } from "flarum/common/extend";
import app from 'flarum/forum/app';
import HeaderPrimary from 'flarum/forum/components/HeaderPrimary';

extend(HeaderPrimary.prototype, 'items', function(items) {
    items.add('SearchEngine', <a href="https://google.com">Google</a>, 5)
});
/*
Error, and doesn't show properly. In the browser console it shows:
    TypeError: Cannot read properties of undefined (reading 'onbeforeupdate')

override(HeaderPrimary.prototype, 'items', function(original) {
    let items = original;
    items.add('author', <a href="https://flarum.org/">FlarumLink</a>);
    return items;
});*/
app.initializers.add("acme-flarum-hello-world", function(app) {
    console.log("Acme/HelloWorld is working!");
})