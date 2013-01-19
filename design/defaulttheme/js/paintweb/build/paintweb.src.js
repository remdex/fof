/*
 * Copyright (C) 2008, 2009, 2010 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2010-06-26 20:35:34 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Minimal JavaScript library which provides functionality for 
 * cross-browser compatibility support.
 */

/**
 * @namespace Holds methods and properties necessary throughout the entire 
 * application.
 */
var pwlib = {};

/**
 * @namespace Holds pre-packaged files.
 * @type Object
 */
pwlib.fileCache = {};

/**
 * @namespace Holds the implementation of each drawing tool.
 *
 * @type Object
 *
 * @see PaintWeb#toolRegister Register a new drawing tool into a PaintWeb 
 * instance.
 * @see PaintWeb#toolActivate Activate a drawing tool in a PaintWeb instance.
 * @see PaintWeb#toolUnregister Unregister a drawing tool from a PaintWeb 
 * instance.
 *
 * @see PaintWeb.config.toolDefault The default tool being activated when 
 * a PaintWeb instance is initialized.
 * @see PaintWeb.config.tools Holds the list of tools to be loaded automatically 
 * when a PaintWeb instance is initialized.
 */
pwlib.tools = {};

/**
 * @namespace Holds all the PaintWeb extensions.
 *
 * @type Object
 * @see PaintWeb#extensionRegister Register a new extension into a PaintWeb 
 * instance.
 * @see PaintWeb#extensionUnregister Unregister an extension from a PaintWeb 
 * instance.
 * @see PaintWeb.config.extensions Holds the list of extensions to be loaded 
 * automatically when a PaintWeb instance is initialized.
 */
pwlib.extensions = {};

/**
 * This function extends objects.
 *
 * @example
 * <code>var <var>obj1</var> = {a: 'a1', b: 'b1', d: 'd1'},
 *     <var>obj2</var> = {a: 'a2', b: 'b2', c: 'c2'};
 * 
 * pwlib.extend(<var>obj1</var>, <var>obj2</var>);</code>
 * 
 * // Now <var>obj1.c == 'c2'</var>, while <var>obj1.a</var>, <var>obj1.b</var>
 * // and <var>obj1.d</var> remain the same.
 *
 * // If <code>pwlib.extend(true, <var>obj1</var>, <var>obj2</var>)</code> is
 * // called, then <var>obj1.a</var>, <var>obj1.b</var>, <var>obj1.c</var>
 * // become all the same as in <var>obj2</var>.
 *
 * @example
 * <code>var <var>obj1</var> = {a: 'a1', b: 'b1', extend: pwlib.extend};
 * <var>obj1</var>.extend({c: 'c1', d: 'd1'});</code>
 *
 * // In this case the destination object which is to be extend is
 * // <var>obj1</var>.
 *
 * @param {Boolean} [overwrite=false] If the first argument is a boolean, then 
 * it will be considered as a boolean flag for overwriting (or not) any existing 
 * methods and properties in the destination object. Thus, any method and 
 * property from the source object will take over those in the destination. The 
 * argument is optional, and if it's omitted, then no method/property will be 
 * overwritten.
 *
 * @param {Object} [destination=this] The second argument is the optional 
 * destination object: the object which will be extended. By default, the 
 * <var>this</var> object will be extended.
 *
 * @param {Object} source The third argument must provide list of methods and 
 * properties which will be added to the destination object.
 */
pwlib.extend = function () {
  var name, src, sval, dval;

  if (typeof arguments[0] === 'boolean') {
    force = arguments[0];
    dest  = arguments[1];
    src   = arguments[2];
  } else {
    force = false;
    dest  = arguments[0];
    src   = arguments[1];
  }

  if (typeof src === 'undefined') {
    src = dest;
    dest = this;
  }

  if (typeof dest === 'undefined') {
    return;
  }

  for (name in src) {
    sval = src[name];
    dval = dest[name];
    if (force || typeof dval === 'undefined') {
      dest[name] = sval;
    }
  }
};

/**
 * Retrieve a string formatted with the provided variables.
 *
 * <p>The language string must be available in the global <var>lang</var> 
 * object.
 *
 * <p>The string can contain any number of variables in the form of 
 * <code>{var_name}</code>.
 *
 * @example
 * lang.table_cells = "The table {name} has {n} cells.";
 *
 * // later ...
 * console.log(pwlib.strf(lang.table_cells, {'name' : 'tbl1', 'n' : 11}));
 * // The output is 'The table tbl1 has 11 cells.'
 *
 * @param {String} str The string you want to output.
 *
 * @param {Object} [vars] The variables you want to set in the language string.
 *
 * @returns {String} The string updated with the variables you provided.
 */
pwlib.strf = function (str, vars) {
  if (!str) {
    return str;
  }

  var re, i;

  for (i in vars) {
    re = new RegExp('{' + i + '}', 'g');
    str = str.replace(re, vars[i]);
  }

  return str;
};

/**
 * Parse a JSON string. This method uses the global JSON parser provided by 
 * the browser natively. The small difference is that this method allows 
 * normal JavaScript comments in the JSON string.
 *
 * @param {String} str The JSON string to parse.
 * @returns The JavaScript object that was parsed.
 */
pwlib.jsonParse = function (str) {
  str = str.replace(/\s*\/\*(\s|.)+?\*\//g, '').
            replace(/^\s*\/\/.*$/gm,        '');

  return JSON.parse(str);
};

/**
 * Load a file from a given URL using XMLHttpRequest.
 *
 * @param {String} url The URL you want to load.
 *
 * @param {Function} handler The <code>onreadystatechange</code> event handler 
 * for the XMLHttpRequest object. Your event handler will always receive the 
 * XMLHttpRequest object as the first parameter.
 *
 * @param {String} [method="GET"] The HTTP method to use for loading the URL.
 *
 * @param {String} [send=null] The string you want to send in an HTTP POST 
 * request.
 *
 * @param {Object} [headers] An object holding the header names and values you 
 * want to set for the request.
 *
 * @returns {XMLHttpRequest} The XMLHttpRequest object created by this method.
 *
 * @throws {TypeError} If the <var>url</var> is not a string.
 */
pwlib.xhrLoad = function (url, handler, method, send, headers) {
  if (typeof url !== 'string') {
    throw new TypeError('The first argument must be a string!');
  }

  if (!method) {
    method = 'GET';
  }

  if (!headers) {
    headers = {};
  }

  if (!send) {
    send = null;
  }

  /** @ignore */
  var xhr = new XMLHttpRequest();
  /** @ignore */
  xhr.onreadystatechange = function () { handler(xhr); };
  xhr.open(method, url);

  for (var header in headers) {
    xhr.setRequestHeader(header, headers[header]);
  }

  xhr.send(send);

  return xhr;
};

/**
 * Check if an URL points to a resource from the same host as the desired one.
 *
 * <p>Note that data URIs always return true.
 *
 * @param {String} url The URL you want to check.
 * @param {String} host The host you want in the URL. The host name can include 
 * the port definition as well.
 *
 * @returns {Boolean} True if the <var>url</var> points to a resource from the 
 * <var>host</var> given, or false otherwise.
 */
pwlib.isSameHost = function (url, host) {
  if (!url || !host) {
    return false;
  }

  var pos = url.indexOf(':'),
      proto = url.substr(0, pos + 1).toLowerCase();

  if (proto === 'data:') {
    return true;
  }

  if (proto !== 'http:' && proto !== 'https:') {
    return false;
  }

  var urlHost = url.replace(/^https?:\/\//i, '');
  pos  = urlHost.indexOf('/');
  if (pos > -1) {
    urlHost = urlHost.substr(0, pos);
  }

  // remove default port (80)
  urlHost = urlHost.replace(/:80$/, '');
  host = host.replace(/:80$/, '');

  if (!urlHost || !host || urlHost !== host) {
    return false;
  }

  return true;
};

/**
 * @class Custom application event.
 *
 * @param {String} type Event type.
 * @param {Boolean} [cancelable=false] Tells if the event can be cancelled or 
 * not.
 *
 * @throws {TypeError} If the <var>type</var> parameter is not a string.
 * @throws {TypeError} If the <var>cancelable</var> parameter is not a string.
 *
 * @see pwlib.appEvents for the application events interface which allows adding 
 * and removing event listeners.
 */
pwlib.appEvent = function (type, cancelable) {
  if (typeof type !== 'string') {
    throw new TypeError('The first argument must be a string');
  } else if (typeof cancelable === 'undefined') {
    cancelable = false;
  } else if (typeof cancelable !== 'boolean') {
    throw new TypeError('The second argument must be a boolean');
  }

  /**
   * Event target object.
   * @type Object
   */
  this.target = null;

  /**
   * Tells if the event can be cancelled or not.
   * @type Boolean
   */
  this.cancelable = cancelable;

  /**
   * Tells if the event has the default action prevented or not.
   * @type Boolean
   */
  this.defaultPrevented = false;

  /**
   * Event type.
   * @type String
   */
  this.type = type;

  /**
   * Prevent the default action of the event.
   */
  this.preventDefault = function () {
    if (cancelable) {
      this.defaultPrevented = true;
    }
  };

  /**
   * Stop the event propagation to other event handlers.
   */
  this.stopPropagation = function () {
    this.propagationStopped_ = true;
  };

  this.toString = function () {
    return '[pwlib.appEvent.' + this.type + ']';
  };
};

/**
 * @class Application initialization event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} state The initialization state.
 * @param {String} [errorMessage] The error message, if any.
 *
 * @throws {TypeError} If the <var>state</var> is not a number.
 */
pwlib.appEvent.appInit = function (state, errorMessage) {
  if (typeof state !== 'number') {
    throw new TypeError('The first argument must be a number.');
  }

  /**
   * Application initialization not started.
   * @constant
   */
  this.INIT_NOT_STARTED = 0;

  /**
   * Application initialization started.
   * @constant
   */
  this.INIT_STARTED = 1;

  /**
   * Application initialization completed successfully.
   * @constant
   */
  this.INIT_DONE = 2;

  /**
   * Application initialization failed.
   * @constant
   */
  this.INIT_ERROR = -1;

  /**
   * Initialization state.
   * @type Number
   */
  this.state = state;

  /**
   * Initialization error message, if any.
   * @type String|null
   */
  this.errorMessage = errorMessage || null;

  pwlib.appEvent.call(this, 'appInit');
};

/**
 * @class Application destroy event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 */
pwlib.appEvent.appDestroy = function () {
  pwlib.appEvent.call(this, 'appDestroy');
};

/**
 * @class GUI show event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 */
pwlib.appEvent.guiShow = function () {
  pwlib.appEvent.call(this, 'guiShow');
};

/**
 * @class GUI hide event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 */
pwlib.appEvent.guiHide = function () {
  pwlib.appEvent.call(this, 'guiHide');
};

/**
 * @class Tool preactivation event. This event is cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID of the new tool being activated.
 * @param {String|null} prevId The ID of the previous tool.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 * @throws {TypeError} If the <var>prevId</var> is not a string or null.
 */
pwlib.appEvent.toolPreactivate = function (id, prevId) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  } else if (prevId !== null && typeof prevId !== 'string') {
    throw new TypeError('The second argument must be a string or null.');
  }

  /**
   * Tool ID.
   * @type String
   */
  this.id = id;

  /**
   * Previous tool ID.
   * @type String
   */
  this.prevId = prevId;

  pwlib.appEvent.call(this, 'toolPreactivate', true);
};

/**
 * @class Tool activation event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID the tool which was activated.
 * @param {String|null} prevId The ID of the previous tool.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 * @throws {TypeError} If the <var>prevId</var> is not a string or null.
 */
pwlib.appEvent.toolActivate = function (id, prevId) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  } else if (prevId !== null && typeof prevId !== 'string') {
    throw new TypeError('The second argument must be a string or null.');
  }

  /**
   * Tool ID.
   * @type String
   */
  this.id = id;

  /**
   * Previous tool ID.
   * @type String
   */
  this.prevId = prevId;

  pwlib.appEvent.call(this, 'toolActivate');
};

/**
 * @class Tool registration event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID of the tool being registered in an active PaintWeb 
 * instance.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 */
pwlib.appEvent.toolRegister = function (id) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  }

  /**
   * Tool ID.
   * @type String
   */
  this.id = id;

  pwlib.appEvent.call(this, 'toolRegister');
};

/**
 * @class Tool removal event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID of the tool being unregistered in an active 
 * PaintWeb instance.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 */
pwlib.appEvent.toolUnregister = function (id) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  }

  /**
   * Tool ID.
   * @type String
   */
  this.id = id;

  pwlib.appEvent.call(this, 'toolUnregister');
};

/**
 * @class Extension registration event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID of the extension being registered in an active 
 * PaintWeb instance.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 */
pwlib.appEvent.extensionRegister = function (id) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  }

  /**
   * Extension ID.
   * @type String
   */
  this.id = id;

  pwlib.appEvent.call(this, 'extensionRegister');
};

/**
 * @class Extension removal event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID of the extension being unregistered in an active 
 * PaintWeb instance.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 */
pwlib.appEvent.extensionUnregister = function (id) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  }

  /**
   * Extension ID.
   * @type String
   */
  this.id = id;

  pwlib.appEvent.call(this, 'extensionUnregister');
};

/**
 * @class Command registration event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID of the command being registered in an active 
 * PaintWeb instance.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 */
pwlib.appEvent.commandRegister = function (id) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  }

  /**
   * Command ID.
   * @type String
   */
  this.id = id;

  pwlib.appEvent.call(this, 'commandRegister');
};

/**
 * @class Command removal event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} id The ID of the command being unregistered in an active 
 * PaintWeb instance.
 *
 * @throws {TypeError} If the <var>id</var> is not a string.
 */
pwlib.appEvent.commandUnregister = function (id) {
  if (typeof id !== 'string') {
    throw new TypeError('The first argument must be a string.');
  }

  /**
   * Command ID.
   * @type String
   */
  this.id = id;

  pwlib.appEvent.call(this, 'commandUnregister');
};

/**
 * @class The image save event. This event is cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} dataURL The data URL generated by the browser holding the 
 * pixels of the image being saved, in PNG format.
 * @param {Number} width The image width.
 * @param {Number} height The image height.
 */
pwlib.appEvent.imageSave = function (dataURL, width, height) {
  /**
   * The image saved by the browser, using the base64 encoding.
   * @type String
   */
  this.dataURL = dataURL;

  /**
   * Image width.
   * @type Number
   */
  this.width = width;

  /**
   * Image height.
   * @type Number
   */
  this.height = height;

  pwlib.appEvent.call(this, 'imageSave', true);
};
/**
 * @class The image save result event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Boolean} successful Tells if the image save was successful or not.
 * @param {String} [url] The image address.
 * @param {String} [urlNew] The new image address. Provide this parameter, if, 
 * for example, you allow saving images from a remote server to a local server.  
 * In such cases the image address changes.
 */
pwlib.appEvent.imageSaveResult = function (successful, url, urlNew) {
  /**
   * Tells if the image save was successful or not.
   * @type String
   */
  this.successful = successful;

  /**
   * The image address.
   * @type String|null
   */
  this.url = url;

  /**
   * The new image address.
   * @type String|null
   */
  this.urlNew = urlNew;

  pwlib.appEvent.call(this, 'imageSaveResult');
};

/**
 * @class History navigation event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} currentPos The new history position.
 * @param {Number} previousPos The previous history position.
 * @param {Number} states The number of history states available.
 *
 * @throws {TypeError} If any of the arguments are not numbers.
 */
pwlib.appEvent.historyUpdate = function (currentPos, previousPos, states) {
  if (typeof currentPos !== 'number' || typeof previousPos !== 'number' || 
      typeof states !== 'number') {
    throw new TypeError('All arguments must be numbers.');
  }

  /**
   * Current history position.
   * @type Number
   */
  this.currentPos = currentPos;

  /**
   * Previous history position.
   * @type Number
   */
  this.previousPos = previousPos;

  /**
   * History states count.
   * @type Number
   */
  this.states = states;

  pwlib.appEvent.call(this, 'historyUpdate');
};

/**
 * @class Image size change event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} width The new image width.
 * @param {Number} height The new image height.
 *
 * @throws {TypeError} If any of the arguments are not numbers.
 */
pwlib.appEvent.imageSizeChange = function (width, height) {
  if (typeof width !== 'number' || typeof height !== 'number') {
    throw new TypeError('Both arguments must be numbers.');
  }

  /**
   * New image width.
   * @type Number
   */
  this.width  = width;

  /**
   * New image height.
   * @type Number
   */
  this.height = height;

  pwlib.appEvent.call(this, 'imageSizeChange');
};

/**
 * @class Canvas size change event. This event is not cancelable.
 *
 * <p>Note that the Canvas size is not the same as the image size. Canvas size 
 * refers to the scaling of the Canvas elements being applied (due to image 
 * zooming or due to browser zoom / DPI).
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} width The new Canvas style width.
 * @param {Number} height The new Canvas style height.
 * @param {Number} scale The new Canvas scaling factor.
 *
 * @throws {TypeError} If any of the arguments are not numbers.
 */
pwlib.appEvent.canvasSizeChange = function (width, height, scale) {
  if (typeof width !== 'number' || typeof height !== 'number' || typeof scale 
      !== 'number') {
    throw new TypeError('All the arguments must be numbers.');
  }

  /**
   * New Canvas style width.
   * @type Number
   */
  this.width  = width;

  /**
   * New Canvas style height.
   * @type Number
   */
  this.height = height;

  /**
   * The new Canvas scaling factor.
   * @type Number
   */
  this.scale  = scale;

  pwlib.appEvent.call(this, 'canvasSizeChange');
};

/**
 * @class Image viewport size change event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} width The new viewport width. This must be a CSS length 
 * value, like "100px", "100%" or "100em".
 *
 * @param {String} height The new viewport height. This must be a CSS length 
 * value, like "100px", "100%" or "100em".
 */
pwlib.appEvent.viewportSizeChange = function (width, height) {
  /**
   * New viewport width.
   * @type String
   */
  this.width  = width;

  /**
   * New viewport height.
   * @type String
   */
  this.height = height;

  pwlib.appEvent.call(this, 'viewportSizeChange');
};


/**
 * @class Image zoom event. This event is cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} zoom The new image zoom level.
 *
 * @throws {TypeError} If the <var>zoom</var> argument is not a number.
 */
pwlib.appEvent.imageZoom = function (zoom) {
  if (typeof zoom !== 'number') {
    throw new TypeError('The first argument must be a number.');
  }

  /**
   * The new image zoom level.
   * @type Number
   */
  this.zoom = zoom;

  pwlib.appEvent.call(this, 'imageZoom', true);
};

/**
 * @class Image crop event. This event is cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} x The crop start position on the x-axis.
 * @param {Number} y The crop start position on the y-axis.
 * @param {Number} width The cropped image width.
 * @param {Number} height The cropped image height.
 *
 * @throws {TypeError} If any of the arguments are not numbers.
 */
pwlib.appEvent.imageCrop = function (x, y, width, height) {
  if (typeof x !== 'number' || typeof y !== 'number' || typeof width !== 
      'number' || typeof height !== 'number') {
    throw new TypeError('All arguments must be numbers.');
  }

  /**
   * The crop start position the x-axis.
   * @type Number
   */
  this.x = x;

  /**
   * The crop start position the y-axis.
   * @type Number
   */
  this.y = y;

  /**
   * The cropped image width.
   * @type Number
   */
  this.width  = width;

  /**
   * The cropped image height.
   * @type Number
   */
  this.height = height;

  pwlib.appEvent.call(this, 'imageCrop', true);
};

/**
 * @class Configuration change event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String|Number|Boolean} value The new value.
 * @param {String|Number|Boolean} previousValue The previous value.
 * @param {String} config The configuration property that just changed.
 * @param {String} group The configuration group where the property is found.
 * @param {Object} groupRef The configuration group object reference.
 *
 * @throws {TypeError} If the <var>prop</var> argument is not a string.
 * @throws {TypeError} If the <var>group</var> argument is not a string.
 * @throws {TypeError} If the <var>groupRef</var> argument is not an object.
 */
pwlib.appEvent.configChange = function (value, previousValue, config, group, 
    groupRef) {
  if (typeof config !== 'string') {
    throw new TypeError('The third argument must be a string.');
  } else if (typeof group !== 'string') {
    throw new TypeError('The fourth argument must be a string.');
  } else if (typeof groupRef !== 'object') {
    throw new TypeError('The fifth argument must be an object.');
  }

  /**
   * The new value.
   */
  this.value = value;

  /**
   * The previous value.
   */
  this.previousValue = previousValue;

  /**
   * Configuration property name.
   * @type String
   */
  this.config = config;

  /**
   * Configuration group name.
   * @type String
   */
  this.group = group;

  /**
   * Reference to the object holding the configuration property.
   * @type Object
   */
  this.groupRef = groupRef;

  pwlib.appEvent.call(this, 'configChange');
};

/**
 * @class Canvas shadows allowed change event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Boolean} allowed Tells the new allowance value.
 *
 * @throws {TypeError} If the argument is not a boolean value.
 */
pwlib.appEvent.shadowAllow = function (allowed) {
  if (typeof allowed !== 'boolean') {
    throw new TypeError('The first argument must be a boolean.');
  }

  /**
   * Tells if the Canvas shadows are allowed or not.
   * @type Boolean
   */
  this.allowed = allowed;

  pwlib.appEvent.call(this, 'shadowAllow');
};

/**
 * @class Clipboard update event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {ImageData} data Holds the clipboard ImageData.
 */
pwlib.appEvent.clipboardUpdate = function (data) {
  /**
   * The clipboard image data.
   * @type ImageData
   */
  this.data = data;

  pwlib.appEvent.call(this, 'clipboardUpdate');
};

/**
 * @class An interface for adding, removing and dispatching of custom 
 * application events.
 *
 * @param {Object} target_ The target for all the events.
 *
 * @see pwlib.appEvent to create application event objects.
 */
pwlib.appEvents = function (target_) {
  /**
   * Holds the list of event types and event handlers.
   *
   * @private
   * @type Object
   */
  var events_ = {};

  var eventID_ = 1;

  /**
   * Add an event listener.
   *
   * @param {String} type The event you want to listen for.
   * @param {Function} handler The event handler.
   *
   * @returns {Number} The event ID.
   *
   * @throws {TypeError} If the <var>type</var> argument is not a string.
   * @throws {TypeError} If the <var>handler</var> argument is not a function.
   *
   * @see pwlib.appEvents#remove to remove events.
   * @see pwlib.appEvents#dispatch to dispatch an event.
   */
  this.add = function (type, handler) {
    if (typeof type !== 'string') {
      throw new TypeError('The first argument must be a string.');
    } else if (typeof handler !== 'function') {
      throw new TypeError('The second argument must be a function.');
    }

    var id = eventID_++;

    if (!(type in events_)) {
      events_[type] = {};
    }

    events_[type][id] = handler;

    return id;
  };

  /**
   * Remove an event listener.
   *
   * @param {String} type The event type.
   * @param {Number} id The event ID.
   *
   * @throws {TypeError} If the <var>type</var> argument is not a string.
   *
   * @see pwlib.appEvents#add to add events.
   * @see pwlib.appEvents#dispatch to dispatch an event.
   */
  this.remove = function (type, id) {
    if (typeof type !== 'string') {
      throw new TypeError('The first argument must be a string.');
    }

    if (!(type in events_) || !(id in events_[type])) {
      return;
    }

    delete events_[type][id];
  };

  /**
   * Dispatch an event.
   *
   * @param {String} type The event type.
   * @param {pwlib.appEvent} ev The event object.
   *
   * @returns {Boolean} True if the <code>event.preventDefault()</code> has been 
   * invoked by one of the event handlers, or false if not.
   *
   * @throws {TypeError} If the <var>type</var> parameter is not a string.
   * @throws {TypeError} If the <var>ev</var> parameter is not an object.
   *
   * @see pwlib.appEvents#add to add events.
   * @see pwlib.appEvents#remove to remove events.
   * @see pwlib.appEvent the generic event object.
   */
  this.dispatch = function (ev) {
    if (typeof ev !== 'object') {
      throw new TypeError('The second argument must be an object.');
    } else if (typeof ev.type !== 'string') {
      throw new TypeError('The second argument must be an application event ' +
        'object.');
    }

    // No event handlers.
    if (!(ev.type in events_)) {
      return false;
    }

    ev.target = target_;

    var id, handlers = events_[ev.type];
    for (id in handlers) {
      handlers[id].call(target_, ev);

      if (ev.propagationStopped_) {
        break;
      }
    }

    return ev.defaultPrevented;
  };
};


/**
 * @namespace Holds browser information.
 */
pwlib.browser = {};

(function () {
var ua = '';

if (window.navigator && window.navigator.userAgent) {
  ua = window.navigator.userAgent.toLowerCase();
}

/**
 * @type Boolean
 */
pwlib.browser.opera = window.opera || /\bopera\b/.test(ua);

/**
 * Webkit is the render engine used primarily by Safari. It's also used by 
 * Google Chrome and GNOME Epiphany.
 *
 * @type Boolean
 */
pwlib.browser.webkit = !pwlib.browser.opera &&
                       /\b(applewebkit|webkit)\b/.test(ua);

/**
 * Firefox uses the Gecko render engine.
 *
 * @type Boolean
 */
// In some variations of the User Agent strings provided by Opera, Firefox is 
// mentioned.
pwlib.browser.firefox = /\bfirefox\b/.test(ua) && !pwlib.browser.opera;

/**
 * Gecko is the render engine used by Firefox and related products.
 *
 * @type Boolean
 */
// Typically, the user agent string of WebKit also mentions Gecko. Additionally, 
// Opera mentions Gecko for tricking some sites.
pwlib.browser.gecko = /\bgecko\b/.test(ua) && !pwlib.browser.opera &&
                      !pwlib.browser.webkit;

/**
 * Microsoft Internet Explorer. The future of computing.
 *
 * @type Boolean
 */
// Again, Opera allows users to easily fake the UA.
pwlib.browser.msie = /\bmsie\b/.test(ua) && !pwlib.browser.opera;

/**
 * Presto is the render engine used by Opera.
 *
 * @type Boolean
 */
// Older versions of Opera did not mention Presto in the UA string.
pwlib.browser.presto = /\bpresto\b/.test(ua) || pwlib.browser.opera;


/**
 * Browser operating system
 *
 * @type String
 */
pwlib.browser.os = (ua.match(/\b(windows|linux)\b/) || [])[1];

/**
 * Tells if the browser is running on an OLPC XO. Typically, only the default 
 * Gecko-based browser includes the OLPC XO tokens in the user agent string.
 *
 * @type Boolean
 */
pwlib.browser.olpcxo = /\bolpc\b/.test(ua) && /\bxo\b/.test(ua);

delete ua;
})();


/**
 * @namespace Holds methods and properties necessary for DOM manipulation.
 */
pwlib.dom = {};

/**
 * @namespace Holds the list of virtual key identifiers and a few characters, 
 * each being associated to a key code commonly used by Web browsers.
 *
 * @private
 */
pwlib.dom.keyNames = {
  Help:          6,
  Backspace:     8,
  Tab:           9,
  Clear:         12,
  Enter:         13,
  Shift:         16,
  Control:       17,
  Alt:           18,
  Pause:         19,
  CapsLock:      20,
  Cancel:        24,
  'Escape':      27,
  Space:         32,
  PageUp:        33,
  PageDown:      34,
  End:           35,
  Home:          36,
  Left:          37,
  Up:            38,
  Right:         39,
  Down:          40,
  PrintScreen:   44,
  Insert:        45,
  'Delete':      46,
  Win:           91,
  ContextMenu:   93,
  '*':           106,
  '+':           107,
  F1:            112,
  F2:            113,
  F3:            114,
  F4:            115,
  F5:            116,
  F6:            117,
  F7:            118,
  F8:            119,
  F9:            120,
  F10:           121,
  F11:           122,
  F12:           123,
  NumLock:       144,
  ';':           186,
  '=':           187,
  ',':           188,
  '-':           189,
  '.':           190,
  '/':           191,
  '`':           192,
  '[':           219,
  '\\':          220,
  ']':           221,
  "'":           222
};

/**
 * @namespace Holds the list of codes, each being associated to a virtual key 
 * identifier.
 *
 * @private
 */
pwlib.dom.keyCodes = {
  /*
   * For almost each key code, these comments give the key name, the 
   * keyIdentifier from the DOM 3 Events spec and the Unicode character 
   * information (if you would use the decimal code for direct conversion to 
   * a character, e.g. String.fromCharCode()). Obviously, the Unicode character 
   * information is not to be used, since these are only virtual key codes (not 
   * really char codes) associated to key names.
   *
   * Each key name in here tries to follow the same style as the defined 
   * keyIdentifiers from the DOM 3 Events. Thus for the Page Down button, 
   * 'PageDown' is used (not other variations like 'pag-up'), and so on.
   *
   * Multiple key codes might be associated to the same key - it's not an error.
   *
   * Note that this list is not an exhaustive key codes list. This means that 
   * for key A or for key 0, the script will do String.fromCharCode(keyCode), to 
   * determine the key. For the case of alpha-numeric keys, this works fine.
   */

  /*
   * Key: Enter
   * Unicode: U+0003 [End of text]
   *
   * Note 1: This keyCode is only used in Safari 2 (older Webkit) for the Enter 
   * key.
   *
   * Note 2: In Gecko this keyCode is used for the Cancel key (see 
   * DOM_VK_CANCEL).
   */
  3: 'Enter',

  /*
   * Key: Help
   * Unicode: U+0006 [Acknowledge]
   *
   * Note: Taken from Gecko (DOM_VK_HELP).
   */
  6: 'Help',

  /*
   * Key: Backspace
   * Unicode: U+0008 [Backspace]
   * keyIdentifier: U+0008
   */
  8: 'Backspace',

  /*
   * Key: Tab
   * Unicode: U+0009 [Horizontal tab]
   * keyIdentifier: U+0009
   */
  9: 'Tab',

  /*
   * Key: Enter
   * Unicode: U+0010 [Line feed (LF) / New line (NL) / End of line (EOL)]
   *
   * Note: Taken from the Unicode characters list. If it ends up as a keyCode in 
   * some event, it's simply considered as being the Enter key.
   */
  10: 'Enter',

  /*
   * Key: NumPad_Center
   * Unicode: U+000C [Form feed]
   * keyIdentifier: Clear
   *
   * Note 1: This keyCode is used when NumLock is off, and the user pressed the 
   * 5 key on the numeric pad.
   *
   * Note 2: Safari 2 (older Webkit) assigns this keyCode to the NumLock key 
   * itself.
   */
  12: 'Clear',

  /*
   * Key: Enter
   * Unicode: U+000D [Carriage return (CR)]
   * keyIdentifier: Enter
   *
   * Note 1: This is the keyCode used by most of the Web browsers when the Enter 
   * key is pressed.
   *
   * Note 2: Gecko associates the DOM_VK_RETURN to this keyCode.
   */
  13: 'Enter',

  /*
   * Key: Enter
   * Unicode: U+000E [Shift out]
   *
   * Note: Taken from Gecko (DOM_VK_ENTER).
   */
  14: 'Enter',

  /*
   * Key: Shift
   * Unicode: U+0010 [Data link escape]
   * keyIdentifier: Shift
   *
   * Note: In older Safari (Webkit) versions Shift+Tab is assigned a different 
   * keyCode: keyCode 25.
   */
  16: 'Shift',

  /*
   * Key: Control
   * Unicode: U+0011 [Device control one]
   * keyIdentifier: Control
   */
  17: 'Control',

  /*
   * Key: Alt
   * Unicode: U+0012 [Device control two]
   * keyIdentifier: Alt
   */
  18: 'Alt',

  /*
   * Key: Pause
   * Unicode: U+0013 [Device control three]
   * keyIdentifier: Pause
   */
  19: 'Pause',

  /*
   * Key: CapsLock
   * Unicode: U+0014 [Device control four]
   * keyIdentifier: CapsLock
   */
  20: 'CapsLock',

  /*
   * Key: Cancel
   * Unicode: U+0018 [Cancel]
   * keyIdentifier: U+0018
   */
  24: 'Cancel',

  /*
   * Key: Escape
   * Unicode: U+001B [Escape]
   * keyIdentifier: U+001B
   */
  27: 'Escape',

  /*
   * Key: Space
   * Unicode: U+0020 [Space]
   * keyIdentifier: U+0020
   */
  32: 'Space',

  /*
   * Key: PageUp or NumPad_North_East
   * Unicode: U+0021 ! [Exclamation mark]
   * keyIdentifier: PageUp
   */
  33: 'PageUp',

  /*
   * Key: PageDown or NumPad_South_East
   * Unicode: U+0022 " [Quotation mark]
   * keyIdentifier: PageDown
   */
  34: 'PageDown',

  /*
   * Key: End or NumPad_South_West
   * Unicode: U+0023 # [Number sign]
   * keyIdentifier: PageDown
   */
  35: 'End',

  /*
   * Key: Home or NumPad_North_West
   * Unicode: U+0024 $ [Dollar sign]
   * keyIdentifier: Home
   */
  36: 'Home',

  /*
   * Key: Left or NumPad_West
   * Unicode: U+0025 % [Percent sign]
   * keyIdentifier: Left
   */
  37: 'Left',

  /*
   * Key: Up or NumPad_North
   * Unicode: U+0026 & [Ampersand]
   * keyIdentifier: Up
   */
  38: 'Up',

  /*
   * Key: Right or NumPad_East
   * Unicode: U+0027 ' [Apostrophe]
   * keyIdentifier: Right
   */
  39: 'Right',

  /*
   * Key: Down or NumPad_South
   * Unicode: U+0028 ( [Left parenthesis]
   * keyIdentifier: Down
   */
  40: 'Down',

  /*
   * Key: PrintScreen
   * Unicode: U+002C , [Comma]
   * keyIdentifier: PrintScreen
   */
  //44: 'PrintScreen',

  /*
   * Key: Insert or NumPad_Insert
   * Unicode: U+002D - [Hyphen-Minus]
   * keyIdentifier: Insert
   */
  45: 'Insert',

  /*
   * Key: Delete or NumPad_Delete
   * Unicode: U+002E . [Full stop / period]
   * keyIdentifier: U+007F
   */
  46: 'Delete',

  /*
   * Key: WinLeft
   * Unicode: U+005B [ [Left square bracket]
   * keyIdentifier: Win
   *
   * Disabled: rarely needed.
   */
  //91: 'Win',

  /*
   * Key: WinRight
   * Unicode: U+005C \ [Reverse solidus / Backslash]
   * keyIdentifier: Win
   */
  //92: 'Win',

  /*
   * Key: Menu/ContextMenu
   * Unicode: U+005D ] [Right square bracket]
   * keyIdentifier: ...
   *
   * Disabled: Is it Meta? Is it Menu, ContextMenu, what? Too much mess.
   */
  //93: 'ContextMenu',

  /*
   * Key: NumPad_0
   * Unicode: U+0060 ` [Grave accent]
   * keyIdentifier: 0
   */
  96: '0',

  /*
   * Key: NumPad_1
   * Unicode: U+0061 a [Latin small letter a]
   * keyIdentifier: 1
   */
  97: '1',

  /*
   * Key: NumPad_2
   * Unicode: U+0062 b [Latin small letter b]
   * keyIdentifier: 2
   */
  98: '2',

  /*
   * Key: NumPad_3
   * Unicode: U+0063 c [Latin small letter c]
   * keyIdentifier: 3
   */
  99: '3',

  /*
   * Key: NumPad_4
   * Unicode: U+0064 d [Latin small letter d]
   * keyIdentifier: 4
   */
  100: '4',

  /*
   * Key: NumPad_5
   * Unicode: U+0065 e [Latin small letter e]
   * keyIdentifier: 5
   */
  101: '5',

  /*
   * Key: NumPad_6
   * Unicode: U+0066 f [Latin small letter f]
   * keyIdentifier: 6
   */
  102: '6',

  /*
   * Key: NumPad_7
   * Unicode: U+0067 g [Latin small letter g]
   * keyIdentifier: 7
   */
  103: '7',

  /*
   * Key: NumPad_8
   * Unicode: U+0068 h [Latin small letter h]
   * keyIdentifier: 8
   */
  104: '8',

  /*
   * Key: NumPad_9
   * Unicode: U+0069 i [Latin small letter i]
   * keyIdentifier: 9
   */
  105: '9',

  /*
   * Key: NumPad_Multiply
   * Unicode: U+0070 j [Latin small letter j]
   * keyIdentifier: U+002A * [Asterisk / Star]
   */
  106: '*',

  /*
   * Key: NumPad_Plus
   * Unicode: U+0071 k [Latin small letter k]
   * keyIdentifier: U+002B + [Plus]
   */
  107: '+',

  /*
   * Key: NumPad_Minus
   * Unicode: U+0073 m [Latin small letter m]
   * keyIdentifier: U+002D + [Hyphen / Minus]
   */
  109: '-',

  /*
   * Key: NumPad_Period
   * Unicode: U+0074 n [Latin small letter n]
   * keyIdentifier: U+002E . [Period]
   */
  110: '.',

  /*
   * Key: NumPad_Division
   * Unicode: U+0075 o [Latin small letter o]
   * keyIdentifier: U+002F / [Solidus / Slash]
   */
  111: '/',

  112: 'F1',                // p
  113: 'F2',                // q
  114: 'F3',                // r
  115: 'F4',                // s
  116: 'F5',                // t
  117: 'F6',                // u
  118: 'F7',                // v
  119: 'F8',                // w
  120: 'F9',                // x
  121: 'F10',               // y
  122: 'F11',               // z
  123: 'F12',               // {

  /*
   * Key: Delete
   * Unicode: U+007F [Delete]
   * keyIdentifier: U+007F
   */
  127: 'Delete',

  /*
   * Key: NumLock
   * Unicode: U+0090 [Device control string]
   * keyIdentifier: NumLock
   */
  144: 'NumLock',

  186: ';',                 // º (Masculine ordinal indicator)
  187: '=',                 // »
  188: ',',                 // ¼
  189: '-',                 // ½
  190: '.',                 // ¾
  191: '/',                 // ¿
  192: '`',                 // À
  219: '[',                 // Û
  220: '\\',                // Ü
  221: ']',                 // Ý
  222: "'"                  // Þ (Latin capital letter thorn)

  //224: 'Win',               // à
  //229: 'WinIME',            // å or WinIME or something else in Webkit
  //255: 'NumLock',           // ÿ, Gecko and Chrome, Windows XP in VirtualBox
  //376: 'NumLock'            // Ÿ, Opera, Windows XP in VirtualBox
};

if (pwlib.browser.gecko) {
  pwlib.dom.keyCodes[3] = 'Cancel'; // DOM_VK_CANCEL
}

/**
 * @namespace Holds a list of common wrong key codes in Web browsers.
 *
 * @private
 */
pwlib.dom.keyCodes_fixes = {
  42:   pwlib.dom.keyNames['*'],        // char * to key *
  47:   pwlib.dom.keyNames['/'],        // char / to key /
  59:   pwlib.dom.keyNames[';'],        // char ; to key ;
  61:   pwlib.dom.keyNames['='],        // char = to key =
  96:   48,                             // NumPad_0 to char 0
  97:   49,                             // NumPad_1 to char 1
  98:   50,                             // NumPad_2 to char 2
  99:   51,                             // NumPad_3 to char 3
  100:  52,                             // NumPad_4 to char 4
  101:  53,                             // NumPad_5 to char 5
  102:  54,                             // NumPad_6 to char 6
  103:  55,                             // NumPad_7 to char 7
  104:  56,                             // NumPad_8 to char 8
  105:  57,                             // NumPad_9 to char 9
  //106:  56,                           // NumPad_Multiply to char 8
  //107:  187,                          // NumPad_Plus to key =
  109:  pwlib.dom.keyNames['-'],        // NumPad_Minus to key -
  110:  pwlib.dom.keyNames['.'],        // NumPad_Period to key .
  111:  pwlib.dom.keyNames['/']         // NumPad_Division to key /
};

/**
 * @namespace Holds the list of broken key codes generated by older Webkit 
 * (Safari 2).
 *
 * @private
 */
pwlib.dom.keyCodes_Safari2 = {
  63232: pwlib.dom.keyNames.Up,               // 38
  63233: pwlib.dom.keyNames.Down,             // 40
  63234: pwlib.dom.keyNames.Left,             // 37
  63235: pwlib.dom.keyNames.Right,            // 39
  63236: pwlib.dom.keyNames.F1,               // 112
  63237: pwlib.dom.keyNames.F2,               // 113
  63238: pwlib.dom.keyNames.F3,               // 114
  63239: pwlib.dom.keyNames.F4,               // 115
  63240: pwlib.dom.keyNames.F5,               // 116
  63241: pwlib.dom.keyNames.F6,               // 117
  63242: pwlib.dom.keyNames.F7,               // 118
  63243: pwlib.dom.keyNames.F8,               // 119
  63244: pwlib.dom.keyNames.F9,               // 120
  63245: pwlib.dom.keyNames.F10,              // 121
  63246: pwlib.dom.keyNames.F11,              // 122
  63247: pwlib.dom.keyNames.F12,              // 123
  63248: pwlib.dom.keyNames.PrintScreen,      // 44
  63272: pwlib.dom.keyNames['Delete'],        // 46
  63273: pwlib.dom.keyNames.Home,             // 36
  63275: pwlib.dom.keyNames.End,              // 35
  63276: pwlib.dom.keyNames.PageUp,           // 33
  63277: pwlib.dom.keyNames.PageDown,         // 34
  63289: pwlib.dom.keyNames.NumLock,          // 144
  63302: pwlib.dom.keyNames.Insert            // 45
};


/**
 * A complete keyboard events cross-browser compatibility layer.
 *
 * <p>Unfortunately, due to the important differences across Web browsers, 
 * simply using the available properties in a single keyboard event is not 
 * enough to accurately determine the key the user pressed. Thus, one needs to 
 * have event handlers for all keyboard-related events <code>keydown</code>, 
 * <code>keypress</code> and <code>keyup</code>.
 *
 * <p>This class provides a complete keyboard event compatibility layer. For any 
 * new instance you provide the DOM element you want to listen events for, and 
 * the event handlers for any of the three events <code>keydown</code> 
 * / <code>keypress</code> / <code>keyup</code>.
 *
 * <p>Your event handlers will receive the original DOM Event object, with 
 * several new properties defined:
 *
 * <ul>
 *   <li><var>event.keyCode_</var> holds the correct code for event key.
 *
 *   <li><var>event.key_</var> holds the key the user pressed. It can be either 
 *   a key name like "PageDown", "Delete", "Enter", or it is a character like 
 *   "A", "1", or "[".
 *
 *   <li><var>event.charCode_</var> holds the Unicode character decimal code.
 *
 *   <li><var>event.char_</var> holds the character generated by the event.
 *
 *   <li><var>event.repeat_</var> is a boolean property telling if the 
 *   <code>keypress</code> event is repeated - the user is holding down the key 
 *   for a long-enough period of time to generate multiple events.
 * </ul>
 *
 * <p>The character-related properties, <var>charCode_</var> and 
 * <var>char_</var> are only available in the <code>keypress</code> and 
 * <code>keyup</code> event objects.
 *
 * <p>This class will ensure that the <code>keypress</code> event is always 
 * fired in Webkit and MSIE for all keys, except modifiers. For modifier keys 
 * like <kbd>Shift</kbd>, <kbd>Control</kbd>, and <kbd>Alt</kbd>, the 
 * <code>keypress</code> event will not be fired, even if the Web browser does 
 * it.
 *
 * <p>Some user agents like Webkit repeat the <code>keydown</code> event while 
 * the user holds down a key. This class will ensure that only the 
 * <code>keypress</code> event is repeated.
 *
 * <p>If you want to prevent the default action for an event, you should prevent 
 * it on <code>keypress</code>. This class will prevent the default action for 
 * <code>keydown</code> if need (in MSIE).
 *
 * @example
 * <code>var <var>klogger</var> = function (<var>ev</var>) {
 *   console.log(<var>ev</var>.type +
 *     ' keyCode_ ' + <var>ev</var>.keyCode_ +
 *     ' key_ ' + <var>ev</var>.key_ +
 *     ' charCode_ ' + <var>ev</var>.charCode_ +
 *     ' char_ ' + <var>ev</var>.char_ +
 *     ' repeat_ ' + <var>ev</var>.repeat_);
 * };
 *
 * var <var>kbListener</var> = new pwlib.dom.KeyboardEventListener(window,
 *               {keydown: <var>klogger</var>,
 *                keypress: <var>klogger</var>,
 *                keyup: <var>klogger</var>});</code>
 *
 * // later when you're done...
 * <code><var>kbListener</var>.detach();</code>
 *
 * @class A complete keyboard events cross-browser compatibility layer.
 *
 * @param {Element} elem_ The DOM Element you want to listen events for.
 *
 * @param {Object} handlers_ The object holding the list of event handlers 
 * associated to the name of each keyboard event you want to listen. To listen 
 * for all the three keyboard events use <code>{keydown: <var>fn1</var>, 
 * keypress: <var>fn2</var>, keyup: <var>fn3</var>}</code>.
 *
 * @throws {TypeError} If the <var>handlers_</var> object does not contain any 
 * event handler.
 */
pwlib.dom.KeyboardEventListener = function (elem_, handlers_) {
  /*
    Technical details:

    For the keyup and keydown events the keyCode provided is that of the virtual 
    key irrespective of other modifiers (e.g. Shift). Generally, during the 
    keypress event, the keyCode holds the Unicode value of the character 
    resulted from the key press, say an alphabetic upper/lower-case char, 
    depending on the actual intent of the user and depending on the currently 
    active keyboard layout.

    Examples:
    * Pressing p you get keyCode 80 in keyup/keydown, and keyCode 112 in 
    keypress.  String.fromCharCode(80) = 'P' and String.fromCharCode(112) = 'p'.
    * Pressing P you get keyCode 80 in all events.
    * Pressing F1 you get keyCode 112 in keyup, keydown and keypress.
    * Pressing 9 you get keyCode 57 in all events.
    * Pressing Shift+9 you get keyCode 57 in keyup/keydown, and keyCode 40 in 
    keypress. String.fromCharCode(57) = '9' and String.fromCharCode(40) = '('.

    * Using the Greek layout when you press v on an US keyboard you get the 
    output character ω. The keyup/keydown events hold keyCode 86 which is V.  
    This does make sense, since it's the virtual key code we are dealing with 
    - not the character code, not the result of pressing the key. The keypress 
    event will hold keyCode 969 (ω).

    * Pressing NumPad_Minus you get keyCode 109 in keyup/keydown and keyCode 45 
    in keypress. Again, this happens because in keyup/keydown you don't get the 
    character code, you get the key code, as indicated above. For
    your information: String.fromCharCode(109) = 'm' and String.fromCharCode(45) 
    = '-'.

    Therefore, we need to map all the codes of several keys, like F1-F12, 
    Escape, Enter, Tab, etc. This map is held by pwlib.dom.keyCodes. It 
    associates, for example, code 112 to F1, or 13 to Enter. This map is used to 
    detect virtual keys in all events.

    (This is only the general story, details about browser-specific differences 
    follow below.)

    If the code given by the browser doesn't appear in keyCode maps, it's used 
    as is.  The key_ returned is that of String.fromCharCode(keyCode).

    In all browsers we consider all events having keyCode <= 32, as being events  
    generated by a virtual key (not a character). As such, the keyCode value is 
    always searched in pwlib.dom.keyCodes.

    As you might notice from the above description, in the keypress event we 
    cannot tell the difference from say F1 and p, because both give the code 
    112. In Gecko and Webkit we can tell the difference because these UAs also 
    set the charCode event property when the key generates a character. If F1 is 
    pressed, or some other virtual key, charCode is never set.

    In Opera the charCode property is never set. However, the 'which' event 
    property is not set for several virtual keys. This means we can tell the 
    difference between a character and a virtual key. However, there's a catch: 
    not *all* virtual keys have the 'which' property unset. Known exceptions: 
    Backspace (8), Tab (9), Enter (13), Shift (16), Control (17), Alt (18), 
    Pause (19), Escape (27), End (35), Home (36), Insert (45), Delete (46) and 
    NumLock (144). Given we already consider any keyCode <= 32 being one of some 
    virtual key, fewer exceptions remain. We only have the End, Home, Insert, 
    Delete and the NumLock keys which cannot be 100% properly detected in the 
    keypress event, in Opera. To properly detect End/Home we can check if the 
    Shift modifier is active or not. If the user wants # instead of End, then 
    Shift must be active. The same goes for $ and Home. Thus we now only have 
    the '-' (Insert) and the '.' (Delete) characters incorrectly detected as 
    being Insert/Delete.
    
    The above brings us to one of the main visible difference, when comparing 
    the pwlib.dom.KeyboardEventListener class and the simple 
    pwlib.dom.KeyboardEvent.getKey() function. In getKey(), for the keypress 
    event we cannot accurately determine the exact key, because it requires
    checking the keyCode used for the keydown event. The KeyboardEventListener
    class monitors all the keyboard events, ensuring a more accurate key 
    detection.

    Different keyboard layouts and international characters are generally 
    supported. Tested and known to work with the Cyrillic alphabet (Greek 
    keyboard layout) and with the US Dvorak keyboard layouts.

    Opera does not fire the keyup event for international characters when 
    running on Linux. For example, this happens with the Greek keyboard layout, 
    when trying Cyrillic characters.

    Gecko gives no keyCode/charCode/which for international characters when 
    running on Linux, in the keyup/keydown events. Thus, all such keys remain 
    unidentified for these two events. For the keypress event there are no 
    issues with such characters.

    Webkit and Konqueror 4 also implement the keyIdentifier property from the 
    DOM 3 Events specification. In theory, this should be great, but it's not 
    without problems.  Sometimes keyCode/charCode/which are all 0, but 
    keyIdentifier is properly set. For several virtual keys the keyIdentifier 
    value is simply 'U+0000'. Thus, the keyIdentifier is used only if the value 
    is not 'Unidentified' / 'U+0000', and only when keyCode/charCode/which are 
    not available.

    Konqueror 4 does not use the 'U+XXXX' notation for Unicode characters. It 
    simply gives the character, directly.

    Additionally, Konqueror seems to have some problems with several keyCodes in 
    keydown/keyup. For example, the key '[' gives keyCode 91 instead of 219.  
    Thus, it effectively gives the Unicode for the character, not the key code.  
    This issue is visible with other key as well.

    NumPad_Clear is unidentified on Linux in all browsers, but it works on 
    Windows.

    In MSIE the keypress event is only fired for characters and for Escape, 
    Space and Enter. Similarly, Webkit only fires the keypress event for 
    characters. However, Webkit does not fire keypress for Escape.

    International characters and different keyboard layouts seem to work fine in 
    MSIE as well.

    As of MSIE 4.0, the keypress event fires for the following keys:
      * Letters: A - Z (uppercase and lowercase)
      * Numerals: 0 - 9
      * Symbols: ! @ # $ % ^ & * ( ) _ - + = < [ ] { } , . / ? \ | ' ` " ~
      * System: Escape (27), Space (32), Enter (13)

    Documentation about the keypress event:
    http://msdn.microsoft.com/en-us/library/ms536939(VS.85).aspx

    As of MSIE 4.0, the keydown event fires for the following keys:
      * Editing: Delete (46), Insert (45)
      * Function: F1 - F12
      * Letters: A - Z (uppercase and lowercase)
      * Navigation: Home, End, Left, Right, Up, Down
      * Numerals: 0 - 9
      * Symbols: ! @ # $ % ^ & * ( ) _ - + = < [ ] { } , . / ? \ | ' ` " ~
      * System: Escape (27), Space (32), Shift (16), Tab (9)

    As of MSIE 5, the event also fires for the following keys:
      * Editing: Backspace (8)
      * Navigation: PageUp (33), PageDown (34)
      * System: Shift+Tab (9)

    Documentation about the keydown event:
    http://msdn.microsoft.com/en-us/library/ms536938(VS.85).aspx

    As of MSIE 4.0, the keyup event fires for the following keys:
      * Editing: Delete, Insert
      * Function: F1 - F12
      * Letters: A - Z (uppercase and lowercase)
      * Navigation: Home (36), End (35), Left (37), Right (39), Up (38), Down (40)
      * Numerals: 0 - 9
      * Symbols: ! @ # $ % ^ & * ( ) _ - + = < [ ] { } , . / ? \ | ' ` " ~
      * System: Escape (27), Space (32), Shift (16), Tab (9)

    As of MSIE 5, the event also fires for the following keys:
      * Editing: Backspace (8)
      * Navigation: PageUp (33), PageDown (34)
      * System: Shift+Tab (9)

    Documentation about the keyup event:
    http://msdn.microsoft.com/en-us/library/ms536940(VS.85).aspx

    For further gory details and a different implementation see:
    http://code.google.com/p/doctype/source/browse/trunk/goog/events/keycodes.js
    http://code.google.com/p/doctype/source/browse/trunk/goog/events/keyhandler.js

    Opera keydown/keyup:
      These events fire for all keys, including for modifiers.
      keyCode is always set.
      charCode is never set.
      which is always set.
      keyIdentifier is always undefined.

    Opera keypress:
      This event fires for all keys, except for modifiers themselves.
      keyCode is always set.
      charCode is never set.
      which is set for all characters. which = 0 for several virtual keys.
      which is known to be set for: Backspace (8), Tab (9), Enter (13), Shift 
      (16), Control (17), Alt (18), Pause (19), Escape (27), End (35), Home 
      (36), Insert (45), Delete (46), NumLock (144).
      which is known to be unset for: F1 - F12, PageUp (33), PageDown (34), Left 
      (37), Up (38), Right (39), Down (40).
      keyIdentifier is always undefined.

    MSIE keyup/keypress/keydown:
      Event firing conditions are described above.
      keyCode is always set.
      charCode is never set.
      which is never set.
      keyIdentifier is always undefined.

    Webkit keydown/keyup:
      These events fires for all keys, including for modifiers.
      keyCode is always set.
      charCode is never set.
      which is always set.
      keyIdentifier is always set.

    Webkit keypress:
      This event fires for characters keys, similarly to MSIE (see above info).
      keyCode is always set.
      charCode is always set for all characters.
      which is always set.
      keyIdentifier is null.

    Gecko keydown/keyup:
      These events fire for all keys, including for modifiers.
      keyCode is always set.
      charCode is never set.
      which is always set.
      keyIdentifier is always undefined.

    Gecko keypress:
      This event fires for all keys, except for modifiers themselves.
      keyCode is only set for virtual keys, not for characters.
      charCode is always set for all characters.
      which is always set for all characters and for the Enter virtual key.
      keyIdentifier is always undefined.

    Another important difference between the KeyboardEventListener class and the 
    getKey() function is that the class tries to ensure that the keypress event 
    is fired for the handler, even if the Web browser does not do it natively.  
    Also, the class tries to provide a consistent approach to keyboard event 
    repetition when the user holds down a key for longer periods of time, by 
    repeating only the keypress event.

    On Linux, Opera, Firefox and Konqueror do not repeat the keydown event, only 
    keypress. On Windows, Opera, Firefox and MSIE do repeat the keydown and 
    keypress events while the user holds down the key. Webkit  repeats the 
    keydown and the keypress (when it fires) events on both systems.

    The default action can be prevented for during keydown in MSIE, and during 
    keypress for the other browsers. In Webkit when keypress doesn't fire, 
    keydown needs to be prevented.

    The KeyboardEventListener class tries to bring consistency. The keydown 
    event never repeats, only the keypress event repeats and it always fires for 
    all keys. The keypress event never fires for modifiers. Events should always 
    be prevented during keypress - the class deals with preventing the event 
    during keydown or keypress as needed in Webkit and MSIE.

    If no code/keyIdentifier is given by the browser, the getKey() function 
    returns null. In the case of the KeyboardEventListener class, keyCode_ 
    / key_ / charCode_ / char_ will be null or undefined.
   */

  /**
   * During a keyboard event flow, this holds the current key code, starting 
   * from the <code>keydown</code> event.
   *
   * @private
   * @type Number
   */
  var keyCode_ = null;

  /**
   * During a keyboard event flow, this holds the current key, starting from the 
   * <code>keydown</code> event.
   *
   * @private
   * @type String
   */
  var key_ = null;

  /**
   * During a keyboard event flow, this holds the current character code, 
   * starting from the <code>keypress</code> event.
   *
   * @private
   * @type Number
   */
  var charCode_ = null;

  /**
   * During a keyboard event flow, this holds the current character, starting 
   * from the <code>keypress</code> event.
   *
   * @private
   * @type String
   */
  var char_ = null;

  /**
   * True if the current keyboard event is repeating. This happens when the user 
   * holds down a key for longer periods of time.
   *
   * @private
   * @type Boolean
   */
  var repeat_ = false;


  if (!handlers_) {
    throw new TypeError('The first argument must be of type an object.');
  }

  if (!handlers_.keydown && !handlers_.keypress && !handlers_.keyup) {
    throw new TypeError('The provided handlers object has no keyboard event' +
        'handler.');
  }

  if (handlers_.keydown && typeof handlers_.keydown !== 'function') {
    throw new TypeError('The keydown event handler is not a function!');
  }
  if (handlers_.keypress && typeof handlers_.keypress !== 'function') {
    throw new TypeError('The keypress event handler is not a function!');
  }
  if (handlers_.keyup && typeof handlers_.keyup !== 'function') {
    throw new TypeError('The keyup event handler is not a function!');
  }

  /**
   * Attach the keyboard event listeners to the current DOM element.
   */
  this.attach = function () {
    keyCode_ = null;
    key_ = null;
    charCode_ = null;
    char_ = null;
    repeat_ = false;

    // FIXME: I have some ideas for a solution to the problem of having multiple 
    // event handlers like these attached to the same element. Somehow, only one 
    // should do all the needed work.

    elem_.addEventListener('keydown',  keydown,  false);
    elem_.addEventListener('keypress', keypress, false);
    elem_.addEventListener('keyup',    keyup,    false);
  };

  /**
   * Detach the keyboard event listeners from the current DOM element.
   */
  this.detach = function () {
    elem_.removeEventListener('keydown',  keydown,  false);
    elem_.removeEventListener('keypress', keypress, false);
    elem_.removeEventListener('keyup',    keyup,    false);

    keyCode_ = null;
    key_ = null;
    charCode_ = null;
    char_ = null;
    repeat_ = false;
  };

  /**
   * Dispatch an event.
   *
   * <p>This function simply invokes the handler for the event of the given 
   * <var>type</var>. The handler receives the <var>ev</var> event.
   *
   * @private
   * @param {String} type The event type to dispatch.
   * @param {Event} ev The DOM Event object to dispatch to the handler.
   */
  function dispatch (type, ev) {
    if (!handlers_[type]) {
      return;
    }

    var handler = handlers_[type];

    if (type === ev.type) {
      handler.call(elem_, ev);

    } else {
      // This happens when the keydown event tries to dispatch a keypress event.

      // FIXME: I could use createEvent() ... food for thought for later.

      /** @ignore */
      var ev_new = {};
      pwlib.extend(ev_new, ev);
      ev_new.type = type;

      // Make sure preventDefault() is not borked...
      /** @ignore */
      ev_new.preventDefault = function () {
        ev.preventDefault();
      };

      handler.call(elem_, ev_new);
    }
  };

  /**
   * The <code>keydown</code> event handler. This function determines the key 
   * pressed by the user, and checks if the <code>keypress</code> event will 
   * fire in the current Web browser, or not. If it does not, a synthetic 
   * <code>keypress</code> event will be fired.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function keydown (ev) {
    var prevKey = key_;

    charCode_ = null;
    char_ = null;

    findKeyCode(ev);

    ev.keyCode_ = keyCode_;
    ev.key_ = key_;
    ev.repeat_ = key_ && prevKey === key_ ? true : false;

    repeat_ = ev.repeat_;

    // When the user holds down a key for a longer period of time, the keypress 
    // event is generally repeated. However, in Webkit keydown is repeated (and 
    // keypress if it fires keypress for the key). As such, we do not dispatch 
    // the keydown event when a key event starts to be repeated.
    if (!repeat_) {
      dispatch('keydown', ev);
    }

    // MSIE and Webkit only fire the keypress event for characters 
    // (alpha-numeric and symbols).
    if (!isModifierKey(key_) && !firesKeyPress(ev)) {
      ev.type_ = 'keydown';
      keypress(ev);
    }
  };

  /**
   * The <code>keypress</code> event handler. This function determines the 
   * character generated by the keyboard event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function keypress (ev) {
    // We reuse the keyCode_/key_ from the keydown event, because ev.keyCode 
    // generally holds the character code during the keypress event.
    // However, if keyCode_ is not available, try to determine the key for this 
    // event as well.
    if (!keyCode_) {
      findKeyCode(ev);
      repeat_ = false;
    }

    ev.keyCode_ = keyCode_;
    ev.key_ = key_;

    findCharCode(ev);

    ev.charCode_ = charCode_;
    ev.char_ = char_;

    // Any subsequent keypress event is considered a repeated keypress (the user 
    // is holding down the key).
    ev.repeat_ = repeat_;
    if (!repeat_) {
      repeat_ = true;
    }

    if (!isModifierKey(key_)) {
      dispatch('keypress', ev);
    }
  };

  /**
   * The <code>keyup</code> event handler.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function keyup (ev) {
    /*
     * Try to determine the keyCode_ for keyup again, even if we might already 
     * have it from keydown. This is needed because the user might press some 
     * key which only generates the keydown and keypress events, after which 
     * a sudden keyup event is fired for a completely different key.
     *
     * Example: in Opera press F2 then Escape. It will first generate two 
     * events, keydown and keypress, for the F2 key. When you press Escape to 
     * close the dialog box, the script receives keyup for Escape.
     */
    findKeyCode(ev);

    ev.keyCode_ = keyCode_;
    ev.key_ = key_;

    // Provide the character info from the keypress event in keyup as well.
    ev.charCode_ = charCode_;
    ev.char_ = char_;

    dispatch('keyup', ev);

    keyCode_ = null;
    key_ = null;
    charCode_ = null;
    char_ = null;
    repeat_ = false;
  };

  /**
   * Tells if the <var>key</var> is a modifier or not.
   *
   * @private
   * @param {String} key The key name.
   * @returns {Boolean} True if the <var>key</var> is a modifier, or false if 
   * not.
   */
  function isModifierKey (key) {
    switch (key) {
      case 'Shift':
      case 'Control':
      case 'Alt':
      case 'Meta':
      case 'Win':
        return true;
      default:
        return false;
    }
  };

  /**
   * Tells if the current Web browser will fire the <code>keypress</code> event 
   * for the current <code>keydown</code> event object.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   * @returns {Boolean} True if the Web browser will fire 
   * a <code>keypress</code> event, or false if not.
   */
  function firesKeyPress (ev) {
    // Gecko does not fire keypress for the Up/Down arrows when the target is an 
    // input element.
    if ((key_ === 'Up' || key_ === 'Down') && pwlib.browser.gecko && ev.target 
        && ev.target.tagName.toLowerCase() === 'input') {
      return false;
    }

    if (!pwlib.browser.msie && !pwlib.browser.webkit) {
      return true;
    }

    // Check if the key is a character key, or not.
    // If it's not a character, then keypress will not fire.
    // Known exceptions: keypress fires for Space, Enter and Escape in MSIE.
    if (key_ && key_ !== 'Space' && key_ !== 'Enter' && key_ !== 'Escape' && 
        key_.length !== 1) {
      return false;
    }

    // Webkit doesn't fire keypress for Escape as well ...
    if (pwlib.browser.webkit && key_ === 'Escape') {
      return false;
    }

    // MSIE does not fire keypress if you hold Control / Alt down, while Shift 
    // is off. Albeit, based on testing I am not completely sure if Shift needs 
    // to be down or not. Sometimes MSIE won't fire keypress even if I hold 
    // Shift down, and sometimes it does. Eh.
    if (pwlib.browser.msie && !ev.shiftKey && (ev.ctrlKey || ev.altKey)) {
      return false;
    }

    return true;
  };

  /**
   * Determine the key and the key code for the current DOM Event object. This 
   * function updates the <var>keyCode_</var> and the <var>key_</var> variables 
   * to hold the result.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function findKeyCode (ev) {
    /*
     * If the event has no keyCode/which/keyIdentifier values, then simply do 
     * not overwrite any existing keyCode_/key_.
     */
    if (ev.type === 'keyup' && !ev.keyCode && !ev.which && (!ev.keyIdentifier || 
          ev.keyIdentifier === 'Unidentified' || ev.keyIdentifier === 'U+0000')) {
      return;
    }

    keyCode_ = null;
    key_ = null;

    // Try to use keyCode/which.
    if (ev.keyCode || ev.which) {
      keyCode_ = ev.keyCode || ev.which;

      // Fix Webkit quirks
      if (pwlib.browser.webkit) {
        // Old Webkit gives keyCode 25 when Shift+Tab is used.
        if (keyCode_ == 25 && this.shiftKey) {
          keyCode_ = pwlib.dom.keyNames.Tab;
        } else if (keyCode_ >= 63232 && keyCode_ in pwlib.dom.keyCodes_Safari2) {
          // Old Webkit gives wrong values for several keys.
          keyCode_ = pwlib.dom.keyCodes_Safari2[keyCode_];
        }
      }

      // Fix keyCode quirks in all browsers.
      if (keyCode_ in pwlib.dom.keyCodes_fixes) {
        keyCode_ = pwlib.dom.keyCodes_fixes[keyCode_];
      }

      key_ = pwlib.dom.keyCodes[keyCode_] || String.fromCharCode(keyCode_);

      return;
    }

    // Try to use ev.keyIdentifier. This is only available in Webkit and 
    // Konqueror 4, each having some quirks. Sometimes the property is needed, 
    // because keyCode/which are not always available.

    var key = null,
        keyCode = null,
        id = ev.keyIdentifier;

    if (!id || id === 'Unidentified' || id === 'U+0000') {
      return;
    }

    if (id.substr(0, 2) === 'U+') {
      // Webkit gives character codes using the 'U+XXXX' notation, as per spec.
      keyCode = parseInt(id.substr(2), 16);

    } else if (id.length === 1) {
      // Konqueror 4 implements keyIdentifier, and they provide the Unicode 
      // character directly, instead of using the 'U+XXXX' notation.
      keyCode = id.charCodeAt(0);
      key = id;

    } else {
      /*
       * Common keyIdentifiers like 'PageDown' are used as they are.
       * We determine the common keyCode used by Web browsers, from the 
       * pwlib.dom.keyNames object.
       */
      keyCode_ = pwlib.dom.keyNames[id] || null;
      key_ = id;

      return;
    }

    // Some keyIdentifiers like 'U+007F' (127: Delete) need to become key names.
    if (keyCode in pwlib.dom.keyCodes && (keyCode <= 32 || keyCode == 127 || 
          keyCode == 144)) {
      key_ = pwlib.dom.keyCodes[keyCode];
    } else {
      if (!key) {
        key = String.fromCharCode(keyCode);
      }

      // Konqueror gives lower-case chars
      key_ = key.toUpperCase();
      if (key !== key_) {
        keyCode = key_.charCodeAt(0);
      }
    }

    // Correct the keyCode, make sure it's a common keyCode, not the Unicode 
    // decimal representation of the character.
    if (key_ === 'Delete' || key_.length === 1 && key_ in pwlib.dom.keyNames) {
      keyCode = pwlib.dom.keyNames[key_];
    }

    keyCode_ = keyCode;
  };

  /**
   * Determine the character and the character code for the current DOM Event 
   * object. This function updates the <var>charCode_</var> and the 
   * <var>char_</var> variables to hold the result.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function findCharCode (ev) {
    charCode_ = null;
    char_ = null;

    // Webkit and Gecko implement ev.charCode.
    if (ev.charCode) {
      charCode_ = ev.charCode;
      char_ = String.fromCharCode(ev.charCode);

      return;
    }

    // Try the keyCode mess.
    if (ev.keyCode || ev.which) {
      var keyCode = ev.keyCode || ev.which;

      var force = false;

      // We accept some keyCodes.
      switch (keyCode) {
        case pwlib.dom.keyNames.Tab:
        case pwlib.dom.keyNames.Enter:
        case pwlib.dom.keyNames.Space:
          force = true;
      }

      // Do not consider the keyCode a character code, if during the keydown 
      // event it was determined the key does not generate a character, unless 
      // it's Tab, Enter or Space.
      if (!force && key_ && key_.length !== 1) {
        return;
      }

      // If the keypress event at hand is synthetically dispatched by keydown, 
      // then special treatment is needed. This happens only in Webkit and MSIE.
      if (ev.type_ === 'keydown') {
        var key = pwlib.dom.keyCodes[keyCode];
        // Check if the keyCode points to a single character.
        // If it does, use it.
        if (key && key.length === 1) {
          charCode_ = key.charCodeAt(0); // keyCodes != charCodes
          char_ = key;
        }
      } else if (keyCode >= 32 || force) {
        // For normal keypress events, we are done.
        charCode_ = keyCode;
        char_ = String.fromCharCode(keyCode);
      }

      if (charCode_) {
        return;
      }
    }

    /*
     * Webkit and Konqueror do not provide a keyIdentifier in the keypress 
     * event, as per spec. However, in the unlikely case when the keyCode is 
     * missing, and the keyIdentifier is available, we use it.
     *
     * This property might be used when a synthetic keypress event is generated 
     * by the keydown event, and keyCode/charCode/which are all not available.
     */

    var c = null,
        charCode = null,
        id = ev.keyIdentifier;

    if (id && id !== 'Unidentified' && id !== 'U+0000' &&
        (id.substr(0, 2) === 'U+' || id.length === 1)) {

      // Characters in Konqueror...
      if (id.length === 1) {
        charCode = id.charCodeAt(0);
        c = id;

      } else {
        // Webkit uses the 'U+XXXX' notation as per spec.
        charCode = parseInt(id.substr(2), 16);
      }

      if (charCode == pwlib.dom.keyNames.Tab ||
          charCode == pwlib.dom.keyNames.Enter ||
          charCode >= 32 && charCode != 127 &&
          charCode != pwlib.dom.keyNames.NumLock) {

        charCode_ = charCode;
        char_ = c || String.fromCharCode(charCode);

        return;
      }
    }

    // Try to use the key determined from the previous keydown event, if it 
    // holds a character.
    if (key_ && key_.length === 1) {
      charCode_ = key_.charCodeAt(0);
      char_ = key_;
    }
  };

  this.attach();
};

// Check out the libmacrame project: http://code.google.com/p/libmacrame.

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-08-24 13:18:05 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the Bézier curve tool implementation.
 */

/**
 * @class The Bézier curve tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.bcurve = function (app) {
  var _self         = this,
      clearInterval = app.win.clearInterval,
      config        = app.config,
      context       = app.buffer.context,
      gui           = app.gui,
      image         = app.image,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval,
      snapXY        = app.toolSnapXY;

  /**
   * Holds the points in the Bézier curve being drawn.
   *
   * @private
   * @type Array
   */
  var points = [];

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the <kbd>Shift</kbd> key is down or not. This is used by the 
   * drawing function.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var shiftKey = false;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  /**
   * The tool deactivation method, used for clearing the buffer.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (points.length > 0) {
      context.clearRect(0, 0, image.width, image.height);
    }

    needsRedraw = false;
    points = [];

    return true;
  };

  /**
   * The <code>mousedown</code> event handler.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousedown = function (ev) {
    if (points.length === 0) {
      gui.statusShow('bcurveSnapping');
      points.push([mouse.x, mouse.y]);
    }

    if (!timer) {
      timer = setInterval(_self.draw, config.toolDrawDelay);
    }

    shiftKey = ev.shiftKey;
    needsRedraw = false;

    return true;
  };

  /**
   * Store the <kbd>Shift</kbd> key state which is used by the drawing function.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousemove = function (ev) {
    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * Draw the Bézier curve, using the available points.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function () {
    if (!needsRedraw) {
      return;
    }

    var n = points.length;

    // Add the temporary point while the mouse button is down.
    if (mouse.buttonDown) {
      if (shiftKey && n === 1) {
        snapXY(points[0][0], points[0][1]);
      }
      points.push([mouse.x, mouse.y]);
      n++;
    }

    var p0 = points[0],
        p1 = points[1],
        p2 = points[2],
        p3 = points[3] || points[2];

    if (mouse.buttonDown) {
      points.pop();
    }

    context.clearRect(0, 0, image.width, image.height);

    if (!n) {
      needsRedraw = false;
      return;
    }

    // Draw the main line
    if (n === 2) {
      context.beginPath();
      context.moveTo(p0[0], p0[1]+2);
      context.lineTo(p1[0], p1[1]+2);

      if (config.shapeType === 'fill') {
        var lineWidth   = context.lineWidth,
            strokeStyle = context.strokeStyle;

        context.lineWidth   = 1;
        context.strokeStyle = context.fillStyle;
      }

      context.stroke();
      context.closePath();

      if (config.shapeType === 'fill') {
        context.lineWidth   = lineWidth;
        context.strokeStyle = strokeStyle;
      }

      needsRedraw = false;
      return;
    }

    // Draw the Bézier curve

    context.beginPath();
    context.moveTo(p0[0], p0[1]);
    context.bezierCurveTo(
      p2[0], p2[1],
      p3[0], p3[1],
      p1[0], p1[1]);

    if (config.shapeType !== 'stroke') {
      context.fill();
    }

    if (config.shapeType !== 'fill') {
      context.stroke();
    }

    context.closePath();

    needsRedraw = false;
  };

  /**
   * The <code>mouseup</code> event handler. This method stores the current 
   * mouse coordinates as a point to be used for drawing the Bézier curve.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mouseup = function (ev) {
    var n = points.length;

    // Allow click+mousemove+click, not only mousedown+mousemove+mouseup.
    // Do this only for the start point.
    if (n === 1 && mouse.x === points[0][0] && mouse.y === points[0][1]) {
      mouse.buttonDown = true;
      return true;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (n === 1 && ev.shiftKey) {
      snapXY(points[0][0], points[0][1]);
    }

    // We need 4 points to draw the Bézier curve: start, end, and two control 
    // points.
    if (n < 4) {
      points.push([mouse.x, mouse.y]);
      needsRedraw = true;
      n++;
    }

    // Make sure the canvas is up-to-date.
    shiftKey = ev.shiftKey;
    _self.draw();

    if (n === 2 || n === 3) {
      gui.statusShow('bcurveControlPoint' + (n-1));
    } else if (n === 4) {
      gui.statusShow('bcurveActive');
      app.layerUpdate();
      points = [];
    }

    return true;
  };

  /**
   * The <code>keydown</code> event handler. This method allows the user to 
   * press the <kbd>Escape</kbd> key to cancel the current drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the keyboard shortcut was recognized, or false 
   * if not.
   */
  this.keydown = function (ev) {
    if (!points.length || ev.kid_ !== 'Escape') {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    context.clearRect(0, 0, image.width, image.height);

    points = [];
    needsRedraw = false;
    mouse.buttonDown = false;

    gui.statusShow('bcurveActive');

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-11-10 20:12:34 +0200 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the color bucket tool implementation, also known as the 
 * flood fill tool.
 */

/**
 * @class The color bucket tool.
 *
 * The implementation here is based on the seed fill algorithm of Paul S.  
 * Heckbert (1990).
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.cbucket = function (app) {
  var _self   = this,
      config  = app.config,
      layer   = app.layer.context,
      buffer  = app.buffer.context,
      iwidth  = app.image.width,
      iheight = app.image.height,
      mouse   = app.mouse;

  var stackMax = 10000; // maximum depth of stack
  var lines = []; // stack of lines
  var pixelNew, layerpix;

  /**
   * The <code>preActivate</code> event handler. This method checks if the 
   * browser implements the <code>getImageData()</code> and 
   * <code>putImageData()</code> context methods.  If not, the color bucket tool 
   * cannot be used.
   *
   * @returns {Boolean} True if the drawing tool can be activated, or false 
   * otherwise.
   */
  this.preActivate = function () {
    // The latest versions of all browsers which implement Canvas, also 
    // implement the getImageData() method. This was only a problem with some 
    // old versions (eg. Opera 9.2).
    if (!layer.getImageData || !layer.putImageData) {
      alert(app.lang.errorCbucketUnsupported);
      return false;
    } else {
      return true;
    }
  };

  /**
   * The <code>activate</code> event handler. Canvas shadow rendering is 
   * disabled.
   */
  this.activate = function () {
    app.shadowDisallow();
  };

  /**
   * The <code>deactivate</code> event handler. Canvas shadow rendering is 
   * allowed once again.
   */
  this.deactivate = function () {
    app.shadowAllow();
  };

  /**
   * The <code>click</code> and <code>contextmenu</code> event handler. This 
   * method performs the flood fill operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the image was modified, or false otherwise.
   */
  this.click = function (ev) {
    // Allow the user to right-click or hold down the Shift key to use the 
    // border color for filling the image.
    if (ev.type === 'contextmenu' || ev.button === 2 || ev.shiftKey) {
      var fillStyle = buffer.fillStyle;
      buffer.fillStyle = buffer.strokeStyle;
      buffer.fillRect(0, 0, 1, 1);
      buffer.fillStyle = fillStyle;
    } else {
      buffer.fillRect(0, 0, 1, 1);
    }

    // Instead of parsing the fillStyle ...
    pixelNew = buffer.getImageData(0, 0, 1, 1);
    pixelNew = [pixelNew.data[0], pixelNew.data[1], pixelNew.data[2], 
             pixelNew.data[3]];

    buffer.clearRect(0, 0, 1, 1);

    var pixelOld = layer.getImageData(mouse.x, mouse.y, 1, 1).data;
    pixelOld = pixelOld[0] + ';' + pixelOld[1] + ';' + pixelOld[2] + ';' 
      + pixelOld[3];

    if (pixelOld === pixelNew.join(';')) {
      return false;
    }

    fill(mouse.x, mouse.y, pixelOld);

    app.historyAdd();

    return true;
  };
  this.contextmenu = this.click;

  /**
   * Fill the image with the current fill color, starting from the <var>x</var> 
   * and <var>y</var> coordinates.
   *
   * @private
   *
   * @param {Number} x The x coordinate for the starting point.
   * @param {Number} y The y coordinate for the starting point.
   * @param {String} pixelOld The old pixel value.
   */
  var fill = function (x, y, pixelOld) {
    var start, x1, x2, dy, tmp, idata;

    pushLine(y, x, x, 1);      // needed in some cases
    pushLine(y + 1, x, x, -1); // seed segment (popped 1st)

    while (lines.length > 0) {
      // pop segment off stack and fill a neighboring scan line
      tmp = lines.pop();
      dy = tmp[3];
      y  = tmp[0] + dy;
      x1 = tmp[1];
      x2 = tmp[2];

      layerpix = null;
      idata = layer.getImageData(0, y, iwidth, 1);
      layerpix = idata.data;

      // segment of scan line y-dy for x1 <= x <= x2 was previously filled, now 
      // explore adjacent pixels in scan line y
      for (x = x1; x >= 0 && pixelRead(x) === pixelOld; x--) {
        pixelWrite(x);
      }

      if (x >= x1) {
        for (x++; x <= x2 && pixelRead(x) !== pixelOld; x++);
        start = x;
        if (x > x2) {
          layer.putImageData(idata, 0, y);
          continue;
        }

      } else {
        start = x + 1;
        if (start < x1) {
          pushLine(y, start, x1 - 1, -dy); // leak on left?
        }

        x = x1 + 1;
      }

      do {
        for (; x < iwidth && pixelRead(x) === pixelOld; x++) {
          pixelWrite(x);
        }

        pushLine(y, start, x - 1, dy);
        if (x > (x2 + 1)) {
          pushLine(y, x2 + 1, x - 1, -dy);  // leak on right?
        }

        for (x++; x <= x2 && pixelRead(x) !== pixelOld; x++);
        start = x;

      } while (x <= x2);

      layer.putImageData(idata, 0, y);
    }

    layerpix = null;
    idata = null;
  };

  var pushLine = function (y, xl, xr, dy) {
    if (lines.length < stackMax && (y+dy) >= 0 && (y+dy) < iheight) {
      lines.push([y, xl, xr, dy]);
    }
  };

  var pixelRead = function (x) {
    var r = 4 * x;
    return layerpix[r] + ';' + layerpix[r+1] + ';' + layerpix[r+2] + ';' 
      + layerpix[r+3];
  };

  var pixelWrite = function (x) {
    var r = 4 * x;
    layerpix[r]   = pixelNew[0];
    layerpix[r+1] = pixelNew[1];
    layerpix[r+2] = pixelNew[2];
    layerpix[r+3] = pixelNew[3];
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-07-02 15:37:38 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the color picker implementation.
 */

/**
 * @class The color picker tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.cpicker = function (app) {
  var _self        = this,
      colormixer   = app.extensions.colormixer,
      context      = app.layer.context,
      gui          = app.gui,
      lang         = app.lang,
      MathRound    = Math.round,
      mouse        = app.mouse;

  /**
   * Holds the ID of the previously active tool. Once the user completes the 
   * color picking operation, the previous tool is activated.
   *
   * @private
   * @type String
   */
  var prevTool = null;

  /**
   * Holds a reference to the target color input. This is a GUI color input 
   * component.
   *
   * @private
   * @type pwlib.guiColorInput
   */
  var targetInput = null;

  /**
   * Holds the previous color values - before the user started picking 
   * a different color.
   *
   * @private
   * @type Object
   */
  var prevColor = null;

  /**
   * Tells if the color mixer is active for the current target input.
   *
   * @private
   * @type Boolean
   */
  var colormixerActive = false;

  /**
   * Tells if the current color values are accepted by the user. This value is 
   * used by the tool deactivation code.
   *
   * @private
   * @type Boolean
   */
  var colorAccepted = false;

  /**
   * The <code>preActivate</code> event handler. This method checks if the 
   * browser implements the <code>getImageData()</code> context method. If not, 
   * the color picker tool cannot be used.
   */
  this.preActivate = function () {
    // The latest versions of all browsers which implement Canvas, also 
    // implement the getImageData() method. This was only a problem with some 
    // old versions (eg. Opera 9.2).
    if (!context.getImageData) {
      alert(lang.errorCpickerUnsupported);
      return false;
    }

    if (app.tool && app.tool._id) {
      prevTool = app.tool._id;
    }

    return true;
  };

  /**
   * The <code>activate</code> event handler. This method determines the current 
   * target input in the Color Mixer, if any. Canvas shadow rendering is 
   * disallowed.
   */
  this.activate = function () {
    // When the color mixer panel is active, the color picker uses the same 
    // target input.
    if (colormixer && colormixer.targetInput) {
      targetInput = gui.colorInputs[colormixer.targetInput.id];
    }

    if (targetInput) {
      gui.statusShow('cpicker_' + targetInput.id);
    } else {
      gui.statusShow('cpickerNormal');
    }

    app.shadowDisallow();
  };

  /**
   * The <code>deactivate</code> event handler. This method allows shadow 
   * rendering again, and resets the color input values if the user did not 
   * accept the new color.
   */
  this.deactivate = function () {
    if (!colorAccepted && targetInput && prevColor) {
      updateColor(null, true);
    }

    app.shadowAllow();
  };

  /**
   * The <code>mousedown</code> event handler. This method starts the color 
   * picking operation.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousedown = function (ev) {
    // We check again, because the user might have opened/closed the color 
    // mixer.
    if (colormixer && colormixer.targetInput) {
      targetInput = gui.colorInputs[colormixer.targetInput.id];
    }

    if (targetInput) {
      colormixerActive = true;
      gui.statusShow('cpicker_' + targetInput.id);
    } else {
      colormixerActive = false;
      gui.statusShow('cpickerNormal');

      // The context menu (right-click). This is unsupported by Opera.
      // Also allow Shift+Click for changing the stroke color (making it easier for Opera users).
      if (ev.button === 2 || ev.shiftKey) {
        targetInput = gui.colorInputs.strokeStyle;
      } else {
        targetInput = gui.colorInputs.fillStyle;
      }
    }

    updatePrevColor();

    _self.mousemove = updateColor;
    updateColor(ev);

    return true;
  };

  /**
   * Perform color update. This function updates the target input or the Color 
   * Mixer to hold the color value under the mouse - it actually performs the 
   * color picking operation.
   *
   * <p>This function is also the <code>mousemove</code> event handler for this 
   * tool.
   *
   * @param {Event} ev The DOM Event object.
   * @param {Boolean} [usePrevColor=false] Tells the function to use the 
   * previous color values we have stored. This is used when the user cancels 
   * the color picking operation.
   */
  function updateColor (ev, usePrevColor) {
    if (!targetInput) {
      return;
    }

    var p = usePrevColor ? prevColor :
              context.getImageData(mouse.x, mouse.y, 1, 1),
        color = {
          red:    p.data[0] / 255,
          green:  p.data[1] / 255,
          blue:   p.data[2] / 255,
          alpha: (p.data[3] / 255).toFixed(3)
        };

    if (colormixerActive) {
      colormixer.color.red   = color.red;
      colormixer.color.green = color.green;
      colormixer.color.blue  = color.blue;
      colormixer.color.alpha = color.alpha;
      colormixer.update_color('rgb');

    } else {
      targetInput.updateColor(color);
    }
  };

  /**
   * The <code>mouseup</code> event handler. This method completes the color 
   * picking operation, and activates the previous tool.
   *
   * <p>The {@link pwlib.appEvent.configChange} application event is also 
   * dispatched for the configuration property associated to the target input.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mouseup = function (ev) {
    if (!targetInput) {
      return false;
    }

    delete _self.mousemove;
    updateColor(ev);
    colorAccepted = true;

    if (!colormixerActive) {
      var color = targetInput.color,
          configProperty = targetInput.configProperty,
          configGroup    = targetInput.configGroup,
          configGroupRef = targetInput.configGroupRef,
          prevVal = configGroupRef[configProperty],
          newVal  = 'rgba(' + MathRound(color.red   * 255) + ',' +
                              MathRound(color.green * 255) + ',' +
                              MathRound(color.blue  * 255) + ',' +
                              color.alpha + ')';

      if (prevVal !== newVal) {
        configGroupRef[configProperty] = newVal;
        app.events.dispatch(new pwlib.appEvent.configChange(newVal, prevVal, 
            configProperty, configGroup, configGroupRef));
      }
    }

    if (prevTool) {
      app.toolActivate(prevTool, ev);
    }

    return true;
  };

  /**
   * The <code>keydown</code> event handler. This method allows the user to 
   * press the <kbd>Escape</kbd> key to cancel the color picking operation. By 
   * doing so, the original color values are restored.
   *
   * @param {Event} ev The DOM Event object.
   * @returns {Boolean} True if the keyboard shortcut was recognized, or false 
   * if not.
   */
  this.keydown = function (ev) {
    if (!prevTool || ev.kid_ !== 'Escape') {
      return false;
    }

    mouse.buttonDown = false;
    app.toolActivate(prevTool, ev);

    return true;
  };

  /**
   * The <code>contextmenu</code> event handler. This method only cancels the 
   * context menu.
   */
  // Unfortunately, the contextmenu event is unsupported by Opera.
  this.contextmenu = function () {
    return true;
  };

  /**
   * Store the color values from the target color input, before this tool 
   * changes the colors. The previous color values are used when the user 
   * decides to cancel the color picking operation.
   * @private
   */
  function updatePrevColor () {
    // If the color mixer panel is visible, then we store the color values from 
    // the color mixer, instead of those from the color input object.
    var color = colormixerActive ? colormixer.color : targetInput.color;

    prevColor = {
      width: 1,
      height: 1,
      data: [
        MathRound(color.red   * 255),
        MathRound(color.green * 255),
        MathRound(color.blue  * 255),
        color.alpha * 255
      ]
    };
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-07-01 18:44:56 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the ellipse tool implementation.
 */

/**
 * @class The ellipse tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.ellipse = function (app) {
  var _self         = this,
      clearInterval = app.win.clearInterval,
      config        = app.config,
      context       = app.buffer.context,
      gui           = app.gui,
      image         = app.image,
      MathMax       = Math.max,
      MathMin       = Math.min,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval;

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the <kbd>Shift</kbd> key is down or not. This is used by the 
   * drawing function.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var shiftKey = false;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  var K = 4*((Math.SQRT2-1)/3);

  /**
   * Holds the starting point on the <var>x</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var x0 = 0;

  /**
   * Holds the starting point on the <var>y</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var y0 = 0;

  /**
   * Tool deactivation event handler.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (mouse.buttonDown) {
      context.clearRect(0, 0, image.width, image.height);
    }

    needsRedraw = false;

    return true;
  };

  /**
   * Initialize the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousedown = function (ev) {
    // The mouse start position
    x0 = mouse.x;
    y0 = mouse.y;

    if (!timer) {
      timer = setInterval(_self.draw, config.toolDrawDelay);
    }
    shiftKey = ev.shiftKey;
    needsRedraw = false;

    gui.statusShow('ellipseMousedown');

    return true;
  };

  /**
   * Store the <kbd>Shift</kbd> key state which is used by the drawing function.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousemove = function (ev) {
    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * Perform the drawing operation. This function is called every few 
   * milliseconds.
   *
   * <p>Hold down the <kbd>Shift</kbd> key to draw a circle.
   * <p>Press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function () {
    if (!needsRedraw) {
      return;
    }

    context.clearRect(0, 0, image.width, image.height);

    var rectx0 = MathMin(mouse.x, x0),
        rectx1 = MathMax(mouse.x, x0),
        recty0 = MathMin(mouse.y, y0),
        recty1 = MathMax(mouse.y, y0);

    /*
      ABCD - rectangle
      A(rectx0, recty0), B(rectx1, recty0), C(rectx1, recty1), D(rectx0, recty1)
    */

    var w = rectx1-rectx0,
        h = recty1-recty0;

    if (!w || !h) {
      needsRedraw = false;
      return;
    }

    // Constrain the ellipse to be a circle
    if (shiftKey) {
      if (w > h) {
        recty1 = recty0+w;
        if (recty0 == mouse.y) {
          recty0 -= w-h;
          recty1 -= w-h;
        }
        h = w;
      } else {
        rectx1 = rectx0+h;
        if (rectx0 == mouse.x) {
          rectx0 -= h-w;
          rectx1 -= h-w;
        }
        w = h;
      }
    }

    // Ellipse radius
    var rx = w/2,
        ry = h/2; 

    // Ellipse center
    var cx = rectx0+rx,
        cy = recty0+ry;

    // Ellipse radius*Kappa, for the Bézier curve control points
    rx *= K;
    ry *= K;

    context.beginPath();

    // startX, startY
    context.moveTo(cx, recty0);

    // Control points: cp1x, cp1y, cp2x, cp2y, destx, desty
    // go clockwise: top-middle, right-middle, bottom-middle, then left-middle
    context.bezierCurveTo(cx + rx, recty0, rectx1, cy - ry, rectx1, cy);
    context.bezierCurveTo(rectx1, cy + ry, cx + rx, recty1, cx, recty1);
    context.bezierCurveTo(cx - rx, recty1, rectx0, cy + ry, rectx0, cy);
    context.bezierCurveTo(rectx0, cy - ry, cx - rx, recty0, cx, recty0);

    if (config.shapeType != 'stroke') {
      context.fill();
    }
    if (config.shapeType != 'fill') {
      context.stroke();
    }

    context.closePath();

    needsRedraw = false;
  };

  /**
   * End the drawing operation, once the user releases the mouse button.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mouseup = function (ev) {
    // Allow click+mousemove, not only mousedown+move+up
    if (mouse.x == x0 && mouse.y == y0) {
      mouse.buttonDown = true;
      return true;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    shiftKey = ev.shiftKey;
    _self.draw();
    app.layerUpdate();
    gui.statusShow('ellipseActive');

    return true;
  };

  /**
   * Allows the user to press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the drawing operation was cancelled, or false if 
   * not.
   */
  this.keydown = function (ev) {
    if (!mouse.buttonDown || ev.kid_ != 'Escape') {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    context.clearRect(0, 0, image.width, image.height);
    mouse.buttonDown = false;
    needsRedraw = false;

    gui.statusShow('ellipseActive');

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-07-29 20:34:06 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the eraser tool implementation.
 */

/**
 * @class The eraser tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.eraser = function (app) {
  var _self         = this,
      bufferContext = app.buffer.context,
      clearInterval = app.win.clearInterval,
      config        = app.config,
      history       = app.history.pos,
      image         = app.image,
      layerContext  = app.layer.context,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval;

  /**
   * The interval ID used for running the erasing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Holds the points needed to be drawn. Each point is added by the 
   * <code>mousemove</code> event handler.
   *
   * @private
   * @type Array
   */
  var points = [];

  /**
   * Holds the starting point on the <var>x</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var x0 = 0;

  /**
   * Holds the starting point on the <var>y</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var y0 = 0;

  var globalOp_  = null,
      lineWidth_ = null;

  /**
   * The tool deactivation event handler. This function clears timers, clears 
   * the canvas and allows shadows to be rendered again.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (mouse.buttonDown) {
      if (globalOp_) {
        layerContext.globalCompositeOperation = globalOp_;
      }
      if (lineWidth_) {
        layerContext.lineWidth = lineWidth_;
      }

      app.historyGoto(history.pos);
    }

    points = [];

    // Allow Canvas shadows.
    app.shadowAllow();
  };

  /**
   * The tool activation event handler. This is run after the tool construction 
   * and after the deactivation of the previous tool. This function simply 
   * disallows the rendering of shadows.
   */
  this.activate = function () {
    // Do not allow Canvas shadows.
    app.shadowDisallow();
  };

  /**
   * Initialize the drawing operation.
   */
  this.mousedown = function () {
    globalOp_  = layerContext.globalCompositeOperation;
    lineWidth_ = layerContext.lineWidth;

    layerContext.globalCompositeOperation = 'destination-out';
    layerContext.lineWidth = bufferContext.lineWidth;

    x0 = mouse.x;
    y0 = mouse.y;

    points = [];
    if (!timer) {
      timer = setInterval(_self.draw, config.toolDrawDelay);
    }

    return true;
  };

  /**
   * Save the mouse coordinates in the array.
   */
  this.mousemove = function () {
    if (mouse.buttonDown) {
      points.push(mouse.x, mouse.y);
    }
  };

  /**
   * Draw the points in the stack. This function is called every few 
   * milliseconds.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function () {
    var i = 0, n = points.length;
    if (!n) {
      return;
    }

    layerContext.beginPath();
    layerContext.moveTo(x0, y0);

    while (i < n) {
      x0 = points[i++];
      y0 = points[i++];
      layerContext.lineTo(x0, y0);
    }

    layerContext.stroke();
    layerContext.closePath();

    points = [];
  };

  /**
   * End the drawing operation, once the user releases the mouse button.
   */
  this.mouseup = function () {
    if (mouse.x == x0 && mouse.y == y0) {
      points.push(x0+1, y0+1);
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }
    _self.draw();

    layerContext.globalCompositeOperation = globalOp_;
    layerContext.lineWidth = lineWidth_;

    app.historyAdd();

    return true;
  };

  /**
   * Allows the user to press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the drawing operation was cancelled, or false if 
   * not.
   */
  this.keydown = function (ev) {
    if (!mouse.buttonDown || ev.kid_ != 'Escape') {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    layerContext.globalCompositeOperation = globalOp_;
    layerContext.lineWidth = lineWidth_;

    mouse.buttonDown = false;
    points = [];

    app.historyGoto(history.pos);

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:


/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-06-15 20:27:08 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the hand tool implementation.
 */

/**
 * @class The hand tool. This tool allows the user to drag the image canvas 
 * inside the viewport.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.hand = function (app) {
  var _self         = this,
      bufferCanvas  = app.buffer.canvas,
      bufferStyle   = bufferCanvas.style,
      config        = app.config;
      clearInterval = app.win.clearInterval,
      image         = app.image,
      MathRound     = Math.round,
      mouse         = app.mouse,
      viewport      = app.gui.elems.viewport,
      vheight       = 0,
      vwidth        = 0,
      setInterval   = app.win.setInterval;

  /**
   * The interval ID used for invoking the viewport drag operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the viewport needs to be scrolled.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsScroll = false;

  /**
   * Holds the previous tool ID.
   *
   * @private
   * @type String
   */
  this.prevTool = null;

  var x0 = 0, y0 = 0,
      x1 = 0, y1 = 0,
      l0 = 0, t0 = 0;

  /**
   * Tool preactivation event handler.
   *
   * @returns {Boolean} True if the tool can become active, or false if not.
   */
  this.preActivate = function () {
    if (!viewport) {
      return false;
    }

    _self.prevTool = app.tool._id;

    // Check if the image canvas can be scrolled within the viewport.

    var cs      = app.win.getComputedStyle(viewport, null),
        bwidth  = parseInt(bufferStyle.width),
        bheight = parseInt(bufferStyle.height);

    vwidth  = parseInt(cs.width),
    vheight = parseInt(cs.height);

    if (vheight < bheight || vwidth < bwidth) {
      return true;
    } else {
      return false;
    }
  };

  /**
   * Tool activation event handler.
   */
  this.activate = function () {
    bufferStyle.cursor = 'move';
    app.shadowDisallow();
  };

  /**
   * Tool deactivation event handler.
   */
  this.deactivate = function (ev) {
    if (timer) {
      clearInterval(timer);
      timer = null;
      app.doc.removeEventListener('mousemove', ev_mousemove, false);
      app.doc.removeEventListener('mouseup',   ev_mouseup, false);
    }

    bufferStyle.cursor = '';
    app.shadowAllow();
  };

  /**
   * Initialize the canvas drag.
   *
   * @param {Event} ev The DOM event object.
   */
  this.mousedown = function (ev) {
    x0 = ev.clientX;
    y0 = ev.clientY;
    l0 = viewport.scrollLeft;
    t0 = viewport.scrollTop;

    needsScroll = false;

    app.doc.addEventListener('mousemove', ev_mousemove, false);
    app.doc.addEventListener('mouseup',   ev_mouseup, false);

    if (!timer) {
      timer = setInterval(viewportScroll, config.toolDrawDelay);
    }

    return true;
  };

  /**
   * The <code>mousemove</code> event handler. This simply stores the current 
   * mouse location.
   *
   * @param {Event} ev The DOM Event object.
   */
  function ev_mousemove (ev) {
    x1 = ev.clientX;
    y1 = ev.clientY;
    needsScroll = true;
  };

  /**
   * Perform the canvas drag operation. This function is called every few 
   * milliseconds.
   *
   * <p>Press <kbd>Escape</kbd> to stop dragging and to get back to the previous 
   * tool.
   */
  function viewportScroll () {
    if (needsScroll) {
      viewport.scrollTop  = t0 - y1 + y0;
      viewport.scrollLeft = l0 - x1 + x0;
      needsScroll = false;
    }
  };

  /**
   * The <code>mouseup</code> event handler.
   */
  function ev_mouseup (ev) {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    ev_mousemove(ev);
    viewportScroll();

    app.doc.removeEventListener('mousemove', ev_mousemove, false);
    app.doc.removeEventListener('mouseup',   ev_mouseup, false);

    mouse.buttonDown = false;
  };

  /**
   * Allows the user to press <kbd>Escape</kbd> to stop dragging the canvas, and 
   * to return to the previous tool.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the key was recognized, or false if not.
   */
  this.keydown = function (ev) {
    if (!_self.prevTool || ev.kid_ != 'Escape') {
      return false;
    }

    app.toolActivate(_self.prevTool, ev);
    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-07-06 16:20:38 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the "Insert image" tool implementation.
 */

// TODO: allow inserting images from a different host, using server-side magic.

/**
 * @class The "Insert image" tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.insertimg = function (app) {
  var _self         = this,
      canvasImage   = app.image,
      clearInterval = app.win.clearInterval,
      config        = app.config,
      context       = app.buffer.context,
      gui           = app.gui,
      lang          = app.lang,
      MathAbs       = Math.abs,
      MathMin       = Math.min,
      MathRound     = Math.round,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval;

  /**
   * Holds the previous tool ID.
   *
   * @private
   * @type String
   */
  var prevTool = app.tool ? app.tool._id : null;

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the <kbd>Shift</kbd> key is down or not. This is used by the 
   * drawing function.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var shiftKey = false;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  /**
   * Holds the starting point on the <var>x</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var x0 = 0;

  /**
   * Holds the starting point on the <var>y</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var y0 = 0;

  /**
   * Tells if the image element loaded or not.
   *
   * @private
   * @type Boolean
   */
  var imageLoaded = false;

  /**
   * Holds the image aspect ratio, used by the resize method.
   *
   * @private
   * @type Number
   */
  var imageRatio = 1;

  /**
   * Holds the DOM image element.
   *
   * @private
   * @type Element
   */
  var imageElement = null;

  /**
   * Holds the image address.
   * @type String
   */
  if (!this.url) {
    this.url = 'http://';
  }

  /**
   * The tool preactivation code. This function asks the user to provide an URL 
   * to the image which is desired to be inserted into the canvas.
   *
   * @returns {Boolean} True if the URL provided is correct. False is returned 
   * if the URL is not provided or if it's incorrect. When false is returned the 
   * tool activation is cancelled.
   */
  this.preActivate = function () {
    if (!gui.elems.viewport) {
      return false;
    }

    _self.url = prompt(lang.promptInsertimg, _self.url);

    if (!_self.url || _self.url.toLowerCase() === 'http://') {
      return false;
    }

    // Remember the URL.
    pwlib.extend(true, _self.constructor.prototype, {url: _self.url});

    if (!pwlib.isSameHost(_self.url, app.win.location.host)) {
      alert(lang.errorInsertimgHost);
      return false;
    }

    return true;
  };

  /**
   * The tool activation event handler. This function is called once the 
   * previous tool has been deactivated.
   */
  this.activate = function () {
    imageElement = new Image();
    imageElement.addEventListener('load', ev_imageLoaded, false);
    imageElement.src = _self.url;

    return true;
  };

  /**
   * The tool deactivation event handler.
   */
  this.deactivate = function () {
    if (imageElement) {
      imageElement = null;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }
    needsRedraw = false;

    context.clearRect(0, 0, canvasImage.width, canvasImage.height);

    return true;
  };

  /**
   * The <code>load</code> event handler for the image element. This method 
   * makes sure the image dimensions are synchronized with the zoom level, and 
   * draws the image on the canvas.
   *
   * @private
   */
  function ev_imageLoaded () {
    // Did the image already load?
    if (imageLoaded) {
      return;
    }

    // The default position for the inserted image is the top left corner of the visible area, taking into consideration the zoom level.
    var x = MathRound(gui.elems.viewport.scrollLeft / canvasImage.canvasScale),
        y = MathRound(gui.elems.viewport.scrollTop  / canvasImage.canvasScale);

    context.clearRect(0, 0, canvasImage.width, canvasImage.height);

    try {
      context.drawImage(imageElement, x, y);
    } catch (err) {
      alert(lang.errorInsertimg);
      return;
    }

    imageLoaded = true;
    needsRedraw = false;

    if (!timer) {
      timer = setInterval(_self.draw, config.toolDrawDelay);
    }

    gui.statusShow('insertimgLoaded');
  };

  /**
   * The <code>mousedown</code> event handler. This method stores the current 
   * mouse location and the image aspect ratio for later reuse by the 
   * <code>draw()</code> method.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousedown = function (ev) {
    if (!imageLoaded) {
      alert(lang.errorInsertimgNotLoaded);
      return false;
    }

    x0 = mouse.x;
    y0 = mouse.y;

    // The image aspect ratio - used by the draw() method when the user holds 
    // the Shift key down.
    imageRatio = imageElement.width / imageElement.height;
    shiftKey = ev.shiftKey;

    gui.statusShow('insertimgResize');

    if (ev.stopPropagation) {
      ev.stopPropagation();
    }
  };

  /**
   * The <code>mousemove</code> event handler.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousemove = function (ev) {
    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * Perform the drawing operation. When the mouse button is not down, the user 
   * is allowed to pick where he/she wants to insert the image element, inside 
   * the canvas. Once the <code>mousedown</code> event is fired, this method 
   * allows the user to resize the image inside the canvas.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function () {
    if (!imageLoaded || !needsRedraw) {
      return;
    }

    context.clearRect(0, 0, canvasImage.width, canvasImage.height);

    // If the user is holding down the mouse button, then allow him/her to 
    // resize the image.
    if (mouse.buttonDown) {
      var w = MathAbs(mouse.x - x0),
          h = MathAbs(mouse.y - y0),
          x = MathMin(mouse.x,  x0),
          y = MathMin(mouse.y,  y0);

      if (!w || !h) {
        needsRedraw = false;
        return;
      }

      // If the Shift key is down, constrain the image to have the same aspect 
      // ratio as the original image element.
      if (shiftKey) {
        if (w > h) {
          if (y == mouse.y) {
            y -= w-h;
          }
          h = MathRound(w/imageRatio);
        } else {
          if (x == mouse.x) {
            x -= h-w;
          }
          w = MathRound(h*imageRatio);
        }
      }

      context.drawImage(imageElement, x, y, w, h);
    } else {
      // If the mouse button is not down, simply allow the user to pick where 
      // he/she wants to insert the image element.
      context.drawImage(imageElement, mouse.x, mouse.y);
    }

    needsRedraw = false;
  };

  /**
   * The <code>mouseup</code> event handler. This method completes the drawing 
   * operation by inserting the image in the layer canvas, and by activating the 
   * previous tool.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mouseup = function (ev) {
    if (!imageLoaded) {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    app.layerUpdate();

    if (prevTool) {
      app.toolActivate(prevTool, ev);
    }

    if (ev.stopPropagation) {
      ev.stopPropagation();
    }
  };

  /**
   * The <code>keydown</code> event handler allows users to press the 
   * <kbd>Escape</kbd> key to cancel the drawing operation and return to the 
   * previous tool.
   *
   * @param {Event} ev The DOM Event object.
   * @returns {Boolean} True if the key was recognized, or false if not.
   */
  this.keydown = function (ev) {
    if (!prevTool || ev.kid_ != 'Escape') {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    mouse.buttonDown = false;
    app.toolActivate(prevTool, ev);

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-06-11 20:23:04 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the line tool implementation.
 */

/**
 * @class The line tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.line = function (app) {
  var _self         = this,
      clearInterval = app.win.clearInterval,
      config        = app.config,
      context       = app.buffer.context,
      gui           = app.gui,
      image         = app.image,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval,
      snapXY        = app.toolSnapXY;

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the <kbd>Shift</kbd> key is down or not. This is used by the 
   * drawing function.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var shiftKey = false;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  /**
   * Holds the starting point on the <var>x</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var x0 = 0;

  /**
   * Holds the starting point on the <var>y</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var y0 = 0;

  /**
   * Tool deactivation event handler.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (mouse.buttonDown) {
      context.clearRect(0, 0, image.width, image.height);
    }

    needsRedraw = false;

    return true;
  };

  /**
   * Initialize the drawing operation, by storing the location of the pointer, 
   * the start position.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousedown = function (ev) {
    x0 = mouse.x;
    y0 = mouse.y;

    if (!timer) {
      timer = setInterval(_self.draw, config.toolDrawDelay);
    }
    shiftKey = ev.shiftKey;
    needsRedraw = false;

    gui.statusShow('lineMousedown');

    return true;
  };

  /**
   * Store the <kbd>Shift</kbd> key state which is used by the drawing function.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousemove = function (ev) {
    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * Perform the drawing operation. This function is called every few 
   * milliseconds.
   *
   * <p>Hold down the <kbd>Shift</kbd> key to draw a straight 
   * horizontal/vertical line.
   * <p>Press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function () {
    if (!needsRedraw) {
      return;
    }

    context.clearRect(0, 0, image.width, image.height);

    // Snapping on the X/Y axis.
    if (shiftKey) {
      snapXY(x0, y0);
    }

    context.beginPath();
    context.moveTo(x0, y0);
    context.lineTo(mouse.x, mouse.y);
    context.stroke();
    context.closePath();

    needsRedraw = false;
  };

  /**
   * End the drawing operation, once the user releases the mouse button.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mouseup = function (ev) {
    // Allow users to click then drag, not only mousedown+drag+mouseup.
    if (mouse.x == x0 && mouse.y == y0) {
      mouse.buttonDown = true;
      return true;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    shiftKey = ev.shiftKey;
    _self.draw();
    gui.statusShow('lineActive');
    app.layerUpdate();

    return true;
  };

  /**
   * Allows the user to press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the drawing operation was cancelled, or false if 
   * not.
   */
  this.keydown = function (ev) {
    if (!mouse.buttonDown || ev.kid_ != 'Escape') {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    context.clearRect(0, 0, image.width, image.height);
    mouse.buttonDown = false;
    needsRedraw = false;

    gui.statusShow('lineActive');

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-06-15 15:25:29 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the pencil tool implementation.
 */

/**
 * @class The drawing pencil.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.pencil = function (app) {
  var _self         = this,
      clearInterval = app.win.clearInterval,
      context       = app.buffer.context,
      image         = app.image,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval;

  /**
   * The interval ID used for running the pencil drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Holds the points needed to be drawn. Each point is added by the 
   * <code>mousemove</code> event handler.
   *
   * @private
   * @type Array
   */
  var points = [];

  /**
   * Holds the last point on the <var>x</var> axis of the image, for the current 
   * drawing operation.
   *
   * @private
   * @type Number
   */
  var x0 = 0;

  /**
   * Holds the last point on the <var>y</var> axis of the image, for the current 
   * drawing operation.
   *
   * @private
   * @type Number
   */
  var y0 = 0;

  /**
   * Tool deactivation event handler.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (mouse.buttonDown) {
      context.clearRect(0, 0, image.width, image.height);
    }

    points = [];
  };

  /**
   * Initialize the drawing operation.
   */
  this.mousedown = function () {
    x0 = mouse.x;
    y0 = mouse.y;

    points = [];
    if (!timer) {
      timer = setInterval(_self.draw, app.config.toolDrawDelay);
    }

    return true;
  };

  /**
   * Save the mouse coordinates in the array.
   */
  this.mousemove = function () {
    if (mouse.buttonDown) {
      points.push(mouse.x, mouse.y);
    }
  };

  /**
   * Draw the points in the stack. This function is called every few 
   * milliseconds.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function () {
    var i = 0, n = points.length;
    if (!n) {
      return;
    }

    context.beginPath();
    context.moveTo(x0, y0);

    while (i < n) {
      x0 = points[i++];
      y0 = points[i++];
      context.lineTo(x0, y0);
    }

    context.stroke();
    context.closePath();

    points = [];
  };

  /**
   * End the drawing operation, once the user releases the mouse button.
   */
  this.mouseup = function () {
    if (mouse.x == x0 && mouse.y == y0) {
      points.push(x0+1, y0+1);
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    _self.draw();
    app.layerUpdate();

    return true;
  };

  /**
   * Allows the user to press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the drawing operation was cancelled, or false if 
   * not.
   */
  this.keydown = function (ev) {
    if (!mouse.buttonDown || ev.kid_ != 'Escape') {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    context.clearRect(0, 0, image.width, image.height);
    mouse.buttonDown = false;
    points = [];

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-06-11 20:28:07 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the polygon tool implementation.
 */

/**
 * @class The polygon tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.polygon = function (app) {
  var _self         = this,
      clearInterval = app.win.clearInterval,
      config        = app.config,
      context       = app.buffer.context,
      gui           = app.gui,
      image         = app.image,
      MathAbs       = Math.abs,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval,
      snapXY        = app.toolSnapXY;

  /**
   * Holds the points in the polygon being drawn.
   *
   * @private
   * @type Array
   */
  var points = [];

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the <kbd>Shift</kbd> key is down or not. This is used by the 
   * drawing function.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var shiftKey = false;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  /**
   * The tool deactivation method, used for clearing the buffer.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (points.length) {
      context.clearRect(0, 0, image.width, image.height);
    }

    needsRedraw = false;
    points = [];

    return true;
  };

  /**
   * The <code>mousedown</code> event handler.
   *
   * @param {Event} ev The DOM Event object.
   * @returns {Boolean} True if the event handler executed, or false if not.
   */
  this.mousedown = function (ev) {
    if (points.length == 0) {
      points.push([mouse.x, mouse.y]);
    }

    if (!timer) {
      timer = setInterval(_self.draw, config.toolDrawDelay);
    }

    shiftKey = ev.shiftKey;
    needsRedraw = false;

    gui.statusShow('polygonMousedown');

    return true;
  };

  /**
   * Store the <kbd>Shift</kbd> key state which is used by the drawing function.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousemove = function (ev) {
    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * Draw the polygon.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function (ev) {
    if (!needsRedraw) {
      return;
    }

    var n = points.length;

    if (!n || (n == 1 && !mouse.buttonDown)) {
      needsRedraw = false;
      return;
    }

    // Snapping on the X/Y axis for the current point (if available).
    if (mouse.buttonDown && shiftKey) {
      snapXY(points[n-1][0], points[n-1][1]);
    }

    context.clearRect(0, 0, image.width, image.height);
    context.beginPath();
    context.moveTo(points[0][0], points[0][1]);

    // Draw the path of the polygon
    for (var i = 0; i < n; i++) {
      context.lineTo(points[i][0], points[i][1]);
    }

    if (mouse.buttonDown) {
      context.lineTo(mouse.x, mouse.y);
    }

    if (config.shapeType != 'stroke') {
      context.fill();
    }

    // In the case where we only have a straight line, draw a stroke even if no 
    // stroke should be drawn, such that the user has better visual feedback.
    if (config.shapeType != 'fill' || n == 1) {
      context.stroke();
    }

    context.closePath();

    needsRedraw = false;
  };

  /**
   * The <code>mouseup</code> event handler.
   *
   * @param {Event} ev The DOM Event object.
   * @returns {Boolean} True if the event handler executed, or false if not.
   */
  this.mouseup = function (ev) {
    var n = points.length;

    // Allow click+mousemove+click, not only mousedown+mousemove+mouseup.
    // Do this only for the first point in the polygon.
    if (n == 1 && mouse.x == points[n-1][0] && mouse.y == points[n-1][1]) {
      mouse.buttonDown = true;
      return true;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    shiftKey = ev.shiftKey;
    needsRedraw = true;

    if (ev.shiftKey) {
      snapXY(points[n-1][0], points[n-1][1]);
    }

    var diffx1 = MathAbs(mouse.x - points[0][0]),
        diffy1 = MathAbs(mouse.y - points[0][1]),
        diffx2 = MathAbs(mouse.x - points[n-1][0]),
        diffy2 = MathAbs(mouse.y - points[n-1][1]);

    // End the polygon if the new point is close enough to the first/last point.
    if ((diffx1 < 5 && diffy1 < 5) || (diffx2 < 5 && diffy2 < 5)) {
      // Add the start point to complete the polygon shape.
      points.push(points[0]);

      _self.draw();
      points = [];

      gui.statusShow('polygonActive');
      app.layerUpdate();

      return true;
    }

    if (n > 3) {
      gui.statusShow('polygonEnd');
    } else {
      gui.statusShow('polygonAddPoint');
    }

    points.push([mouse.x, mouse.y]);
    _self.draw();

    return true;
  };

  /**
   * The <code>keydown</code> event handler. This method allows the user to 
   * cancel drawing the current polygon, using the <kbd>Escape</kbd> key. The 
   * <kbd>Enter</kbd> key can be used to accept the current polygon shape, and 
   * end the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the keyboard shortcut was recognized, or false 
   * if not.
   */
  this.keydown = function (ev) {
    var n = points.length;
    if (!n || (ev.kid_ != 'Escape' && ev.kid_ != 'Enter')) {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }
    mouse.buttonDown = false;

    if (ev.kid_ == 'Escape') {
      context.clearRect(0, 0, image.width, image.height);
      needsRedraw = false;

    } else if (ev.kid_ == 'Enter') {
      // Add the point of the last mousemove event, and the start point, to 
      // complete the polygon.
      points.push([mouse.x, mouse.y]);
      points.push(points[0]);
      needsRedraw = true;
      _self.draw();
      app.layerUpdate();
    }

    points = [];
    gui.statusShow('polygonActive');

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-06-11 20:21:13 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the rectangle tool implementation.
 */

/**
 * @class The rectangle tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.rectangle = function (app) {
  var _self         = this,
      clearInterval = app.win.clearInterval,
      config        = app.config,
      context       = app.buffer.context,
      gui           = app.gui,
      image         = app.image,
      MathAbs       = Math.abs,
      MathMin       = Math.min,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval;

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the <kbd>Shift</kbd> key is down or not. This is used by the 
   * drawing function.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var shiftKey = false;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  /**
   * Holds the starting point on the <var>x</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var x0 = 0;

  /**
   * Holds the starting point on the <var>y</var> axis of the image, for the 
   * current drawing operation.
   *
   * @private
   * @type Number
   */
  var y0 = 0;

  /**
   * Tool deactivation event handler.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    if (mouse.buttonDown) {
      context.clearRect(0, 0, image.width, image.height);
    }

    needsRedraw = false;
  };

  /**
   * Initialize the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousedown = function (ev) {
    x0 = mouse.x;
    y0 = mouse.y;

    if (!timer) {
      timer = setInterval(_self.draw, config.toolDrawDelay);
    }
    shiftKey = ev.shiftKey;
    needsRedraw = false;

    gui.statusShow('rectangleMousedown');

    return true;
  };

  /**
   * Store the <kbd>Shift</kbd> key state which is used by the drawing function.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousemove = function (ev) {
    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * Perform the drawing operation. This function is called every few 
   * milliseconds.
   *
   * <p>Hold down the <kbd>Shift</kbd> key to draw a square.
   * <p>Press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw = function () {
    if (!needsRedraw) {
      return;
    }

    context.clearRect(0, 0, image.width, image.height);

    var x = MathMin(mouse.x,  x0),
        y = MathMin(mouse.y,  y0),
        w = MathAbs(mouse.x - x0),
        h = MathAbs(mouse.y - y0);

    if (!w || !h) {
      needsRedraw = false;
      return;
    }

    // Constrain the shape to a square
    if (shiftKey) {
      if (w > h) {
        if (y == mouse.y) {
          y -= w-h;
        }
        h = w;
      } else {
        if (x == mouse.x) {
          x -= h-w;
        }
        w = h;
      }
    }

    if (config.shapeType != 'stroke') {
      context.fillRect(x, y, w, h);
    }

    if (config.shapeType != 'fill') {
      context.strokeRect(x, y, w, h);
    }

    needsRedraw = false;
  };

  /**
   * End the drawing operation, once the user releases the mouse button.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mouseup = function (ev) {
    // Allow click+mousemove, not only mousedown+move+up
    if (mouse.x == x0 && mouse.y == y0) {
      mouse.buttonDown = true;
      return true;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    shiftKey = ev.shiftKey;
    _self.draw();
    app.layerUpdate();
    gui.statusShow('rectangleActive');

    return true;
  };

  /**
   * Allows the user to press <kbd>Escape</kbd> to cancel the drawing operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the drawing operation was cancelled, or false if 
   * not.
   */
  this.keydown = function (ev) {
    if (!mouse.buttonDown || ev.kid_ != 'Escape') {
      return false;
    }

    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    context.clearRect(0, 0, image.width, image.height);
    mouse.buttonDown = false;
    needsRedraw = false;

    gui.statusShow('rectangleActive');

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:


/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-07-02 16:07:14 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the selection tool implementation.
 */

/**
 * @class The selection tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.selection = function (app) {
  var _self         = this,
      appEvent      = pwlib.appEvent,
      bufferContext = app.buffer.context,
      clearInterval = app.win.clearInterval,
      config        = app.config.selection,
      gui           = app.gui,
      image         = app.image,
      lang          = app.lang,
      layerCanvas   = app.layer.canvas,
      layerContext  = app.layer.context,
      marqueeStyle  = null,
      MathAbs       = Math.abs,
      MathMin       = Math.min,
      MathRound     = Math.round,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval,
      snapXY        = app.toolSnapXY;

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  /**
   * The selection has been dropped, and the mouse button is down. The user has 
   * two choices: he releases the mouse button, thus the selection is dropped 
   * and the tool switches to STATE_NONE, or he moves the mouse in order to 
   * start a new selection (STATE_DRAWING).
   * @constant
   */
  this.STATE_PENDING = -1;

  /**
   * No selection is available.
   * @constant
   */
  this.STATE_NONE = 0;

  /**
   * The user is drawing a selection.
   * @constant
   */
  this.STATE_DRAWING = 1;

  /**
   * The selection rectangle is available.
   * @constant
   */
  this.STATE_SELECTED = 2;

  /**
   * The user is dragging/moving the selection rectangle.
   * @constant
   */
  this.STATE_DRAGGING = 3;

  /**
   * The user is resizing the selection rectangle.
   * @constant
   */
  this.STATE_RESIZING = 4;

  /**
   * Selection state. Known states:
   *
   * <ul>
   *   <li>{@link pwlib.tools.selection#STATE_PENDING} - Selection dropped after 
   *   the <code>mousedown</code> event is fired. The script can switch to 
   *   STATE_DRAWING if the mouse moves, or to STATE_NONE if it does not 
   *   (allowing the user to drop the selection).
   *
   *   <li>{@link pwlib.tools.selection#STATE_NONE} - No selection is available.
   *
   *   <li>{@link pwlib.tools.selection#STATE_DRAWING} - The user is drawing the 
   *   selection rectangle.
   *
   *   <li>{@link pwlib.tools.selection#STATE_SELECTED} - The selection 
   *   rectangle is available.
   *
   *   <li>{@link pwlib.tools.selection#STATE_DRAGGING} - The user is 
   *   dragging/moving the current selection.
   *
   *   <li>{@link pwlib.tools.selection#STATE_RESIZING} - The user is resizing 
   *   the current selection.
   * </ul>
   *
   * @type Number
   * @default STATE_NONE
   */
  this.state = this.STATE_NONE;

  /**
   * Holds the starting point on the <var>x</var> axis of the image, for any 
   * ongoing operation.
   *
   * @private
   * @type Number
   */
  var x0 = 0;

  /**
   * Holds the starting point on the <var>y</var> axis of the image, for the any  
   * ongoing operation.
   *
   * @private
   * @type Number
   */
  var y0 = 0;

  /**
   * Holds selection information and image.
   * @type Object
   */
  this.selection = {
    /**
     * Selection start point, on the <var>x</var> axis.
     * @type Number
     */
    x: 0,

    /**
     * Selection start point, on the <var>y</var> axis.
     * @type Number
     */
    y: 0,

    /**
     * Selection width.
     * @type Number
     */
    width: 0,

    /**
     * Selection height.
     * @type Number
     */
    height: 0,

    /**
     * Selection original width. The user can make a selection rectangle of 
     * a given width and height, but after that he/she can resize the selection.
     * @type Number
     */
    widthOriginal: 0,

    /**
     * Selection original height. The user can make a selection rectangle of 
     * a given width and height, but after that he/she can resize the selection.
     * @type Number
     */
    heightOriginal: 0,

    /**
     * Tells if the selected ImageData has been cut out or not from the 
     * layerContext.
     *
     * @type Boolean
     * @default false
     */
    layerCleared: false,

    /**
     * Selection marquee/border element.
     * @type HTMLElement
     */
    marquee: null,

    /**
     * Selection buffer context which holds the selected pixels.
     * @type CanvasRenderingContext2D
     */
    context: null,

    /**
     * Selection buffer canvas which holds the selected pixels.
     * @type HTMLCanvasElement
     */
    canvas: null
  };

  /**
   * The area type under the current mouse location.
   * 
   * <p>When the selection is available the mouse location can be on top/inside 
   * the selection rectangle, on the border of the selection, or outside the 
   * selection.
   *
   * <p>Possible values: 'in', 'out', 'border'.
   *
   * @private
   * @type String
   * @default 'out'
   */
  var mouseArea = 'out';

  /**
   * The resize type. If the mouse is on top of the selection border, then the 
   * selection can be resized. The direction of the resize operation is 
   * determined by the location of the mouse.
   * 
   * <p>While the user resizes the selection this variable can hold the 
   * following values: 'n' (North), 'ne' (North-East), 'e' (East), 'se' 
   * (South-East), 's' (South), 'sw' (South-West), 'w' (West), 'nw' 
   * (North-West).
   *
   * @private
   * @type String
   * @default null
   */
  var mouseResize = null;

  // shorthands / private variables
  var sel = this.selection,
      borderDouble = config.borderWidth * 2,
      ev_canvasSizeChangeId = null,
      ev_configChangeId = null,
      ctrlKey = false,
      shiftKey = false;

  /**
   * The last selection rectangle that was drawn. This is used by the selection 
   * drawing functions.
   *
   * @private
   * @type Object
   */
  // We avoid retrieving the mouse coordinates during the mouseup event, due to 
  // the Opera bug DSK-232264.
  var lastSel = null;

  /**
   * The tool preactivation code. This function prepares the selection canvas 
   * element.
   *
   * @returns {Boolean} True if the activation did not fail, or false otherwise.  
   * If false is returned, the selection tool cannot be activated.
   */
  this.preActivate = function () {
    if (!('canvasContainer' in gui.elems)) {
      alert(lang.errorToolActivate);
      return false;
    }

    // The selection image buffer.
    sel.canvas = app.doc.createElement('canvas');
    if (!sel.canvas) {
      alert(lang.errorToolActivate);
      return false;
    }

    sel.canvas.width  = 5;
    sel.canvas.height = 5;

    sel.context = sel.canvas.getContext('2d');
    if (!sel.context) {
      alert(lang.errorToolActivate);
      return false;
    }

    sel.marquee = app.doc.createElement('div');
    if (!sel.marquee) {
      alert(lang.errorToolActivate);
      return false;
    }
    sel.marquee.className = gui.classPrefix + 'selectionMarquee';
    marqueeStyle = sel.marquee.style;

    return true;
  };

  /**
   * The tool activation code. This method sets-up multiple event listeners for 
   * several target objects.
   */
  this.activate = function () {
    // Older browsers do not support get/putImageData, thus non-transparent 
    // selections cannot be used.
    if (!layerContext.putImageData || !layerContext.getImageData) {
      config.transparent = true;
    }

    marqueeHide();

    marqueeStyle.borderWidth = config.borderWidth + 'px';
    sel.marquee.addEventListener('mousedown', marqueeMousedown, false);
    sel.marquee.addEventListener('mousemove', marqueeMousemove, false);
    sel.marquee.addEventListener('mouseup',   marqueeMouseup,   false);

    gui.elems.canvasContainer.appendChild(sel.marquee);

    // Disable the Canvas shadow.
    app.shadowDisallow();

    // Application event listeners.
    ev_canvasSizeChangeId = app.events.add('canvasSizeChange', 
        ev_canvasSizeChange);
    ev_configChangeId = app.events.add('configChange', ev_configChange);

    // Register selection-related commands
    app.commandRegister('selectionCrop',   _self.selectionCrop);
    app.commandRegister('selectionDelete', _self.selectionDelete);
    app.commandRegister('selectionFill',   _self.selectionFill);

    if (!timer) {
      timer = setInterval(timerFn, app.config.toolDrawDelay);
    }

    return true;
  };

  /**
   * The tool deactivation code. This removes all event listeners and cleans up 
   * the document.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }

    _self.selectionMerge();

    sel.marquee.removeEventListener('mousedown', marqueeMousedown, false);
    sel.marquee.removeEventListener('mousemove', marqueeMousemove, false);
    sel.marquee.removeEventListener('mouseup',   marqueeMouseup,   false);

    marqueeStyle = null;
    gui.elems.canvasContainer.removeChild(sel.marquee);

    delete sel.context, sel.canvas, sel.marquee;

    // Re-enable canvas shadow.
    app.shadowAllow();

    // Remove the application event listeners.
    if (ev_canvasSizeChangeId) {
      app.events.remove('canvasSizeChange', ev_canvasSizeChangeId);
    }
    if (ev_configChangeId) {
      app.events.remove('configChange', ev_configChangeId);
    }

    // Unregister selection-related commands
    app.commandUnregister('selectionCrop');
    app.commandUnregister('selectionDelete');
    app.commandUnregister('selectionFill');

    return true;
  };

  /**
   * The <code>mousedown</code> event handler. Depending on the mouse location, 
   * this method does initiate different selection operations: drawing, 
   * dropping, dragging or resizing.
   *
   * <p>Hold the <kbd>Control</kbd> key down to temporarily toggle the 
   * transformation mode.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousedown = function (ev) {
    if (_self.state !== _self.STATE_NONE &&
        _self.state !== _self.STATE_SELECTED) {
      return false;
    }

    // Update the current mouse position, this is used as the start position for most of the operations.
    x0 = mouse.x;
    y0 = mouse.y;

    shiftKey = ev.shiftKey;
    ctrlKey = ev.ctrlKey;
    lastSel = null;

    // No selection is available, then start drawing a selection.
    if (_self.state === _self.STATE_NONE) {
      _self.state = _self.STATE_DRAWING;
      marqueeStyle.display = '';
      gui.statusShow('selectionDraw');

      return true;
    }

    // STATE_SELECTED: selection available.
    mouseAreaUpdate();

    /*
     * Check if the user clicked outside the selection: drop the selection, 
     * switch to STATE_PENDING, clear the image buffer and put the current 
     * selection buffer in the image layer.
     *
     * If the user moves the mouse without taking the finger off the mouse 
     * button, then a new selection rectangle will start to be drawn: the script 
     * will switch to STATE_DRAWING.
     *
     * If the user simply takes the finger off the mouse button (mouseup), then 
     * the script will switch to STATE_NONE (no selection available).
     */
    switch (mouseArea) {
      case 'out':
        _self.state = _self.STATE_PENDING;
        marqueeHide();
        gui.statusShow('selectionActive');
        selectionMergeStrict();

        return true;

      case 'in':
        // The mouse area: 'in' for drag.
        _self.state = _self.STATE_DRAGGING;
        gui.statusShow('selectionDrag');
        break;

      case 'border':
        // 'border' for resize (the user is clicking on the borders).
        _self.state = _self.STATE_RESIZING;
        gui.statusShow('selectionResize');
    }

    // Temporarily toggle the transformation mode if the user holds the Control 
    // key down.
    if (ev.ctrlKey) {
      config.transform = !config.transform;
    }

    // If there's any ImageData currently in memory, which was "cut" out from 
    // the current layer, then put it back on the layer. This needs to be done 
    // only when the selection.transform mode is not active - that's when the 
    // drag/resize operation only changes the selection, not the pixels 
    // themselves.
    if (sel.layerCleared && !config.transform) {
      selectionMergeStrict();

    } else if (!sel.layerCleared && config.transform) {
      // When the user starts dragging/resizing the ImageData we must cut out 
      // the current selection from the image layer.
      selectionBufferInit();
    }

    return true;
  };

  /**
   * The <code>mousemove</code> event handler.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mousemove = function (ev) {
    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * The timer function. When the mouse button is down, this method performs the 
   * dragging/resizing operation. When the mouse button is not down, this method 
   * simply tracks the mouse location for the purpose of determining the area 
   * being pointed at: the selection, the borders, or if the mouse is outside 
   * the selection.
   * @private
   */
  function timerFn () {
    if (!needsRedraw) {
      return;
    }

    switch (_self.state) {
      case _self.STATE_PENDING:
        // selection dropped, switch to draw selection
        _self.state = _self.STATE_DRAWING;
        marqueeStyle.display = '';
        gui.statusShow('selectionDraw');

      case _self.STATE_DRAWING:
        selectionDraw();
        break;

      case _self.STATE_SELECTED:
        mouseAreaUpdate();
        break;

      case _self.STATE_DRAGGING:
        selectionDrag();
        break;

      case _self.STATE_RESIZING:
        selectionResize();
    }

    needsRedraw = false;
  };

  /**
   * The <code>mouseup</code> event handler. This method ends any selection 
   * operation.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.selectionChange} 
   * application event when the selection state is changed or when the selection 
   * size/location is updated.
   *
   * @param {Event} ev The DOM Event object.
   */
  this.mouseup = function (ev) {
    // Allow click+mousemove+click, not only mousedown+move+up
    if (_self.state !== _self.STATE_PENDING &&
        mouse.x === x0 && mouse.y === y0) {
      return true;
    }

    needsRedraw = false;

    shiftKey = ev.shiftKey;
    if (ctrlKey) {
      config.transform = !config.transform;
    }

    if (_self.state === _self.STATE_PENDING) {
      // Selection dropped? If yes, switch to the no selection state.
      _self.state = _self.STATE_NONE;
      app.events.dispatch(new appEvent.selectionChange(_self.state));

      return true;

    } else if (!lastSel) {
      _self.state = _self.STATE_NONE;
      marqueeHide();
      gui.statusShow('selectionActive');
      app.events.dispatch(new appEvent.selectionChange(_self.state));

      return true;
    }

    sel.x = lastSel.x;
    sel.y = lastSel.y;

    if ('width' in lastSel) {
      sel.width  = lastSel.width;
      sel.height = lastSel.height;
    }

    _self.state = _self.STATE_SELECTED;

    app.events.dispatch(new appEvent.selectionChange(_self.state, sel.x, sel.y, 
          sel.width, sel.height));

    gui.statusShow('selectionAvailable');

    return true;
  };

  /**
   * The <code>mousedown</code> event handler for the selection marquee element.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function marqueeMousedown (ev) {
    if (mouse.buttonDown) {
      return;
    }
    mouse.buttonDown = true;

    ev.preventDefault();

    _self.mousedown(ev);
  };

  /**
   * The <code>mousemove</code> event handler for the selection marquee element.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function marqueeMousemove (ev) {
    if ('layerX' in ev) {
      mouse.x = MathRound((this.offsetLeft + ev.layerX) / image.canvasScale);
      mouse.y = MathRound((this.offsetTop  + ev.layerY) / image.canvasScale);
    } else if ('offsetX' in ev) {
      mouse.x = MathRound((this.offsetLeft + ev.offsetX) / image.canvasScale);
      mouse.y = MathRound((this.offsetTop  + ev.offsetY) / image.canvasScale);
    }

    shiftKey = ev.shiftKey;
    needsRedraw = true;
  };

  /**
   * The <code>mouseup</code> event handler for the selection marquee element.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function marqueeMouseup (ev) {
    if (!mouse.buttonDown) {
      return;
    }
    mouse.buttonDown = false;

    ev.preventDefault();

    _self.mouseup(ev);
  };

  /**
   * Hide the selection marquee element.
   * @private
   */
  function marqueeHide () {
    marqueeStyle.display = 'none';
    marqueeStyle.top     = '-' + (borderDouble + 50) + 'px';
    marqueeStyle.left    = '-' + (borderDouble + 50) + 'px';
    marqueeStyle.width   = '1px';
    marqueeStyle.height  = '1px';
    marqueeStyle.cursor  = '';
  };

  /**
   * Perform the selection rectangle drawing operation.
   *
   * @private
   */
  function selectionDraw () {
    var x = MathMin(mouse.x,  x0),
        y = MathMin(mouse.y,  y0),
        w = MathAbs(mouse.x - x0),
        h = MathAbs(mouse.y - y0);

    // Constrain the shape to a square.
    if (shiftKey) {
      if (w > h) {
        if (y === mouse.y) {
          y -= w-h;
        }
        h = w;
      } else {
        if (x === mouse.x) {
          x -= h-w;
        }
        w = h;
      }
    }

    var mw = w * image.canvasScale - borderDouble,
        mh = h * image.canvasScale - borderDouble;

    if (mw < 1 || mh < 1) {
      lastSel = null;
      return;
    }

    marqueeStyle.top    = (y * image.canvasScale) + 'px';
    marqueeStyle.left   = (x * image.canvasScale) + 'px';
    marqueeStyle.width  = mw + 'px';
    marqueeStyle.height = mh + 'px';

    lastSel = {'x': x, 'y': y, 'width': w, 'height': h};
  };

  /**
   * Perform the selection drag operation.
   *
   * @private
   *
   * @returns {false|Array} False is returned if the selection is too small, 
   * otherwise an array of two elements is returned. The array holds the 
   * selection coordinates, x and y.
   */
  function selectionDrag () {
    // Snapping on the X/Y axis
    if (shiftKey) {
      snapXY(x0, y0);
    }

    var x = sel.x + mouse.x - x0,
        y = sel.y + mouse.y - y0;

    // Dragging the ImageData
    if (config.transform) {
      bufferContext.clearRect(0, 0, image.width, image.height);

      if (!config.transparent) {
        bufferContext.fillRect(x, y, sel.width, sel.height);
      }

      // Parameters:
      // source image, dest x, dest y, dest width, dest height
      bufferContext.drawImage(sel.canvas, x, y, sel.width, sel.height);
    }

    marqueeStyle.top  = (y * image.canvasScale) + 'px';
    marqueeStyle.left = (x * image.canvasScale) + 'px';

    lastSel = {'x': x, 'y': y};
  };

  /**
   * Perform the selection resize operation.
   *
   * @private
   *
   * @returns {false|Array} False is returned if the selection is too small, 
   * otherwise an array of four elements is returned. The array holds the 
   * selection information: x, y, width and height.
   */
  function selectionResize () {
    var diffx = mouse.x - x0,
        diffy = mouse.y - y0,
        x     = sel.x,
        y     = sel.y,
        w     = sel.width,
        h     = sel.height;

    switch (mouseResize) {
      case 'nw':
        x += diffx;
        y += diffy;
        w -= diffx;
        h -= diffy;
        break;
      case 'n':
        y += diffy;
        h -= diffy;
        break;
      case 'ne':
        y += diffy;
        w += diffx;
        h -= diffy;
        break;
      case 'e':
        w += diffx;
        break;
      case 'se':
        w += diffx;
        h += diffy;
        break;
      case 's':
        h += diffy;
        break;
      case 'sw':
        x += diffx;
        w -= diffx;
        h += diffy;
        break;
      case 'w':
        x += diffx;
        w -= diffx;
        break;
      default:
        lastSel = null;
        return;
    }

    if (!w || !h) {
      lastSel = null;
      return;
    }

    // Constrain the rectangle to have the same aspect ratio as the initial 
    // rectangle.
    if (shiftKey) {
      var p  = sel.width / sel.height,
          w2 = w,
          h2 = h;

      switch (mouseResize.charAt(0)) {
        case 'n':
        case 's':
          w2 = MathRound(h*p);
          break;
        default:
          h2 = MathRound(w/p);
      }

      switch (mouseResize) {
        case 'nw':
        case 'sw':
          x -= w2 - w;
          y -= h2 - h;
      }

      w = w2;
      h = h2;
    }

    if (w < 0) {
      x += w;
      w *= -1;
    }
    if (h < 0) {
      y += h;
      h *= -1;
    }

    var mw   = w * image.canvasScale - borderDouble,
        mh   = h * image.canvasScale - borderDouble;

    if (mw < 1 || mh < 1) {
      lastSel = null;
      return;
    }

    // Resizing the ImageData
    if (config.transform) {
      bufferContext.clearRect(0, 0, image.width, image.height);

      if (!config.transparent) {
        bufferContext.fillRect(x, y, w, h);
      }

      // Parameters:
      // source image, dest x, dest y, dest width, dest height
      bufferContext.drawImage(sel.canvas, x, y, w, h);
    }

    marqueeStyle.top    = (y * image.canvasScale) + 'px';
    marqueeStyle.left   = (x * image.canvasScale) + 'px';
    marqueeStyle.width  = mw + 'px';
    marqueeStyle.height = mh + 'px';

    lastSel = {'x': x, 'y': y, 'width': w, 'height': h};
  };

  /**
   * Determine the are where the mouse is located: if it is inside or outside of 
   * the selection rectangle, or on the selection border.
   * @private
   */
  function mouseAreaUpdate () {
    var border = config.borderWidth / image.canvasScale,
        cursor = '',
        x1_out = sel.x + sel.width,
        y1_out = sel.y + sel.height,
        x1_in  = x1_out - border,
        y1_in  = y1_out - border,
        x0_out = sel.x,
        y0_out = sel.y,
        x0_in  = sel.x + border,
        y0_in  = sel.y + border;

    mouseArea = 'out';

    // Inside the rectangle
    if (mouse.x < x1_in && mouse.y < y1_in &&
        mouse.x > x0_in && mouse.y > y0_in) {
      cursor = 'move';
      mouseArea = 'in';

    } else {
      // On one of the borders (north/south)
      if (mouse.x >= x0_out && mouse.x <= x1_out &&
          mouse.y >= y0_out && mouse.y <= y0_in) {
        cursor = 'n';

      } else if (mouse.x >= x0_out && mouse.x <= x1_out &&
                 mouse.y >= y1_in  && mouse.y <= y1_out) {
        cursor = 's';
      }

      // West/east
      if (mouse.y >= y0_out && mouse.y <= y1_out &&
          mouse.x >= x0_out && mouse.x <= x0_in) {
        cursor += 'w';

      } else if (mouse.y >= y0_out && mouse.y <= y1_out &&
                 mouse.x >= x1_in  && mouse.x <= x1_out) {
        cursor += 'e';
      }

      if (cursor !== '') {
        mouseResize = cursor;
        cursor += '-resize';
        mouseArea = 'border';
      }
    }

    // Due to bug 126457 Opera will not automatically update the cursor, 
    // therefore they will not see any visual feedback.
    if (cursor !== marqueeStyle.cursor) {
      marqueeStyle.cursor = cursor;
    }
  };

  /**
   * The <code>canvasSizeChange</code> application event handler. This method 
   * makes sure the selection size stays in sync.
   *
   * @private
   * @param {pwlib.appEvent.canvasSizeChange} ev The application event object.
   */
  function ev_canvasSizeChange (ev) {
    if (_self.state !== _self.STATE_SELECTED) {
      return;
    }

    marqueeStyle.top    = (sel.y      * ev.scale) + 'px';
    marqueeStyle.left   = (sel.x      * ev.scale) + 'px';
    marqueeStyle.width  = (sel.width  * ev.scale - borderDouble) + 'px';
    marqueeStyle.height = (sel.height * ev.scale - borderDouble) + 'px';
  };

  /**
   * The <code>configChange</code> application event handler. This method makes 
   * sure that changes to the selection transparency configuration option are 
   * applied.
   *
   * @private
   * @param {pwlib.appEvent.configChange} ev The application event object.
   */
  function ev_configChange (ev) {
    // Continue only if the selection rectangle is available.
    if (ev.group !== 'selection' || ev.config !== 'transparent' ||
        !config.transform || _self.state !== _self.STATE_SELECTED) {
      return;
    }

    if (!sel.layerCleared) {
      selectionBufferInit();
    }

    bufferContext.clearRect(sel.x, sel.y, sel.width, sel.height);

    if (!ev.value) {
      bufferContext.fillRect(sel.x, sel.y, sel.width, sel.height);
    }

    // Draw the updated selection
    bufferContext.drawImage(sel.canvas, sel.x, sel.y, sel.width, sel.height);
  };

  /**
   * Initialize the selection buffer, when the user starts dragging or resizing 
   * the selected pixels.
   *
   * @private
   */
  function selectionBufferInit () {
    var x = sel.x,
        y = sel.y,
        w = sel.width,
        h = sel.height,
        sumX = sel.x + sel.width,
        sumY = sel.y + sel.height,
        dx = 0, dy = 0;

    sel.widthOriginal  = w;
    sel.heightOriginal = h;

    if (x < 0) {
      w += x;
      dx -= x;
      x = 0;
    }
    if (y < 0) {
      h += y;
      dy -= y;
      y = 0;
    }

    if (sumX > image.width) {
      w = image.width - sel.x;
    }
    if (sumY > image.height) {
      h = image.height - sel.y;
    }

    if (!config.transparent) {
      bufferContext.fillRect(x, y, w, h);
    }

    // Parameters:
    // source image, src x, src y, src w, src h, dest x, dest y, dest w, dest h
    bufferContext.drawImage(layerCanvas, x, y, w, h, x, y, w, h);

    sel.canvas.width  = sel.widthOriginal;
    sel.canvas.height = sel.heightOriginal;

    // Also put the selected pixels into the selection buffer.
    sel.context.drawImage(layerCanvas, x, y, w, h, dx, dy, w, h);

    // Clear the selected pixels from the image
    layerContext.clearRect(x, y, w, h);
    sel.layerCleared = true;

    app.historyAdd();
  };

  /**
   * Perform the selection buffer merge onto the current image layer.
   * @private
   */
  function selectionMergeStrict () {
    if (!sel.layerCleared) {
      return;
    }

    if (!config.transparent) {
      layerContext.fillRect(sel.x, sel.y, sel.width, sel.height);
    }

    layerContext.drawImage(sel.canvas, sel.x, sel.y, sel.width, sel.height);
    bufferContext.clearRect(sel.x, sel.y, sel.width, sel.height);

    sel.layerCleared  = false;
    sel.canvas.width  = 5;
    sel.canvas.height = 5;

    app.historyAdd();
  };

  /**
   * Merge the selection buffer onto the current image layer.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.selectionChange} 
   * application event.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectionMerge = function () {
    if (_self.state !== _self.STATE_SELECTED) {
      return false;
    }

    selectionMergeStrict();

    _self.state = _self.STATE_NONE;
    marqueeHide();
    gui.statusShow('selectionActive');

    app.events.dispatch(new appEvent.selectionChange(_self.state));

    return true;
  };

  /**
   * Select all the entire image.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.selectionChange} 
   * application event.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectAll = function () {
    if (_self.state !== _self.STATE_NONE && _self.state !== 
        _self.STATE_SELECTED) {
      return false;
    }

    if (_self.state === _self.STATE_SELECTED) {
      selectionMergeStrict();
    } else {
      _self.state = _self.STATE_SELECTED;
      marqueeStyle.display = '';
    }

    sel.x      = 0;
    sel.y      = 0;
    sel.width  = image.width;
    sel.height = image.height;

    marqueeStyle.top     = '0px';
    marqueeStyle.left    = '0px';
    marqueeStyle.width   = (sel.width*image.canvasScale  - borderDouble) + 'px';
    marqueeStyle.height  = (sel.height*image.canvasScale - borderDouble) + 'px';

    mouseAreaUpdate();

    app.events.dispatch(new appEvent.selectionChange(_self.state, sel.x, sel.y, 
          sel.width, sel.height));

    return true;
  };

  /**
   * Cut the selected pixels. The associated ImageData is stored in {@link 
   * PaintWeb#clipboard}.
   *
   * <p>This method dispatches two application events: {@link 
   * pwlib.appEvent.clipboardUpdate} and {@link pwlib.appEvent.selectionChange}.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectionCut = function () {
    if (!_self.selectionCopy()) {
      return false;
    }

    if (sel.layerCleared) {
      bufferContext.clearRect(sel.x, sel.y, sel.width, sel.height);

      sel.canvas.width  = 5;
      sel.canvas.height = 5;
      sel.layerCleared = false;

    } else {
      layerContext.clearRect(sel.x, sel.y, sel.width, sel.height);
      app.historyAdd();
    }

    _self.state = _self.STATE_NONE;
    marqueeHide();

    app.events.dispatch(new appEvent.selectionChange(_self.state));
    gui.statusShow('selectionActive');

    return true;
  };

  /**
   * Copy the selected pixels. The associated ImageData is stored in {@link 
   * PaintWeb#clipboard}.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.clipboardUpdate} 
   * application event.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectionCopy = function () {
    if (_self.state !== _self.STATE_SELECTED) {
      return false;
    }

    if (!layerContext.getImageData || !layerContext.putImageData) {
      alert(lang.errorClipboardUnsupported);
      return false;
    }

    if (!sel.layerCleared) {
      var w    = sel.width,
          h    = sel.height,
          sumX = sel.width  + sel.x;
          sumY = sel.height + sel.y;

      if (sumX > image.width) {
        w = image.width - sel.x;
      }
      if (sumY > image.height) {
        h = image.height - sel.y;
      }

      try {
        app.clipboard = layerContext.getImageData(sel.x, sel.y, w, h);
      } catch (err) {
        alert(lang.failedSelectionCopy);
        return false;
      }

    } else {
      try {
        app.clipboard = sel.context.getImageData(0, 0, sel.widthOriginal, 
            sel.heightOriginal);
      } catch (err) {
        alert(lang.failedSelectionCopy);
        return false;
      }
    }

    app.events.dispatch(new appEvent.clipboardUpdate(app.clipboard));

    return true;
  };

  /**
   * Paste an image from the "clipboard". The {@link PaintWeb#clipboard} object 
   * must be an ImageData. This method will generate a new selection which will 
   * hold the pasted image.
   *
   * <p>The {@link pwlib.appEvent.selectionChange} application event is 
   * dispatched.
   *
   * <p>If the {@link PaintWeb.config.selection.transform} value is false, then 
   * it becomes true. The {@link pwlib.appEvent.configChange} application is 
   * then dispatched.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.clipboardPaste = function () {
    if (!app.clipboard || _self.state !== _self.STATE_NONE && _self.state !== 
        _self.STATE_SELECTED) {
      return false;
    }

    if (!layerContext.getImageData || !layerContext.putImageData) {
      alert(lang.errorClipboardUnsupported);
      return false;
    }

    // The default position for the pasted image is the top left corner of the 
    // visible area, taking into consideration the zoom level.
    var x = MathRound(gui.elems.viewport.scrollLeft / image.canvasScale),
        y = MathRound(gui.elems.viewport.scrollTop  / image.canvasScale),
        w = app.clipboard.width,
        h = app.clipboard.height;

    sel.canvas.width  = w;
    sel.canvas.height = h;
    sel.context.putImageData(app.clipboard, 0, 0);

    if (_self.state === _self.STATE_SELECTED) {
      bufferContext.clearRect(sel.x, sel.y, sel.width, sel.height);
    } else {
      _self.state = _self.STATE_SELECTED;
    }

    if (!config.transparent) {
      bufferContext.fillRect(x, y, w, h);
    }
    bufferContext.drawImage(sel.canvas, x, y, w, h);

    sel.widthOriginal  = sel.width  = w;
    sel.heightOriginal = sel.height = h;
    sel.x = x;
    sel.y = y;
    sel.layerCleared = true;

    marqueeStyle.top     = (y * image.canvasScale) + 'px';
    marqueeStyle.left    = (x * image.canvasScale) + 'px';
    marqueeStyle.width   = (w * image.canvasScale - borderDouble) + 'px';
    marqueeStyle.height  = (h * image.canvasScale - borderDouble) + 'px';
    marqueeStyle.display = '';

    if (!config.transform) {
      config.transform = true;
      app.events.dispatch(new appEvent.configChange(true, false, 'transform', 
            'selection', config));
    }

    mouseAreaUpdate();

    app.events.dispatch(new appEvent.selectionChange(_self.state, sel.x, sel.y, 
          sel.width, sel.height));

    gui.statusShow('selectionAvailable');

    return true;
  };

  /**
   * Perform selection delete.
   *
   * <p>This method changes the {@link PaintWeb.config.selection.transform} 
   * value to false if the current selection has pixels that are currently being 
   * manipulated. In such cases, the {@link pwlib.appEvent.configChange} 
   * application event is also dispatched.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectionDelete = function () {
    // Delete the pixels from the image if they are not deleted already.
    if (_self.state !== _self.STATE_SELECTED) {
      return false;
    }

    if (!sel.layerCleared) {
      layerContext.clearRect(sel.x, sel.y, sel.width, sel.height);
      app.historyAdd();

    } else {
      bufferContext.clearRect(sel.x, sel.y, sel.width, sel.height);
      sel.layerCleared  = false;
      sel.canvas.width  = 5;
      sel.canvas.height = 5;

      if (config.transform) {
        config.transform = false;
        app.events.dispatch(new appEvent.configChange(false, true, 'transform', 
              'selection', config));
      }
    }

    return true;
  };

  /**
   * Drop the current selection.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.selectionChange} 
   * application event.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectionDrop = function () {
    if (_self.state !== _self.STATE_SELECTED) {
      return false;
    }

    if (sel.layerCleared) {
      bufferContext.clearRect(sel.x, sel.y, sel.width, sel.height);
      sel.canvas.width  = 5;
      sel.canvas.height = 5;
      sel.layerCleared  = false;
    }

    _self.state = _self.STATE_NONE;

    marqueeHide();
    gui.statusShow('selectionActive');

    app.events.dispatch(new appEvent.selectionChange(_self.state));

    return true;
  };

  /**
   * Fill the available selection with the current 
   * <var>bufferContext.fillStyle</var>.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectionFill = function () {
    if (_self.state !== _self.STATE_SELECTED) {
      return false;
    }

    if (sel.layerCleared) {
      sel.context.fillStyle = bufferContext.fillStyle;
      sel.context.fillRect(0, 0, sel.widthOriginal, sel.heightOriginal);
      bufferContext.fillRect(sel.x, sel.y, sel.width, sel.height);

    } else {
      layerContext.fillStyle = bufferContext.fillStyle;
      layerContext.fillRect(sel.x, sel.y, sel.width, sel.height);
      app.historyAdd();
    }

    return true;
  };

  /**
   * Crop the image to selection width and height. The selected pixels become 
   * the image itself.
   *
   * <p>This method invokes the {@link this#selectionMerge} and {@link 
   * PaintWeb#imageCrop} methods.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.selectionCrop = function () {
    if (_self.state !== _self.STATE_SELECTED) {
      return false;
    }

    _self.selectionMerge();

    var w    = sel.width,
        h    = sel.height,
        sumX = sel.x + w,
        sumY = sel.y + h;

    if (sumX > image.width) {
      w -= sumX - image.width;
    }
    if (sumY > image.height) {
      h -= sumY - image.height;
    }

    app.imageCrop(sel.x, sel.y, w, h);

    return true;
  };

  /**
   * The <code>keydown</code> event handler. This method calls selection-related 
   * commands associated to keyboard shortcuts.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the keyboard shortcut was recognized, or false 
   * if not.
   *
   * @see PaintWeb.config.selection.keys holds the keyboard shortcuts 
   * configuration.
   */
  this.keydown = function (ev) {
    switch (ev.kid_) {
      case config.keys.transformToggle:
        // Toggle the selection transformation mode.
        config.transform = !config.transform;
        app.events.dispatch(new appEvent.configChange(config.transform, 
              !config.transform, 'transform', 'selection', config));
        break;

      case config.keys.selectionCrop:
        return _self.selectionCrop(ev);

      case config.keys.selectionDelete:
        return _self.selectionDelete(ev);

      case config.keys.selectionDrop:
        return _self.selectionDrop(ev);

      case config.keys.selectionFill:
        return _self.selectionFill(ev);

      default:
        return false;
    }

    return true;
  };
};

/**
 * @class Selection change event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} state Tells the new state of the selection.
 * @param {Number} [x] Selection start position on the x-axis of the image.
 * @param {Number} [y] Selection start position on the y-axis of the image.
 * @param {Number} [width] Selection width.
 * @param {Number} [height] Selection height.
 */
pwlib.appEvent.selectionChange = function (state, x, y, width, height) {
  /**
   * No selection is available.
   * @constant
   */
  this.STATE_NONE = 0;

  /**
   * Selection available.
   * @constant
   */
  this.STATE_SELECTED = 2;

  /**
   * Selection state.
   * @type Number
   */
  this.state = state;

  /**
   * Selection location on the x-axis of the image.
   * @type Number
   */
  this.x = x;

  /**
   * Selection location on the y-axis of the image.
   * @type Number
   */
  this.y = y;

  /**
   * Selection width.
   * @type Number
   */
  this.width  = width;

  /**
   * Selection height.
   * @type Number
   */
  this.height = height;

  pwlib.appEvent.call(this, 'selectionChange');
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-08-27 20:30:01 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the text tool implementation.
 */

// TODO: make this tool nicer to use.

/**
 * @class The text tool.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.text = function (app) {
  var _self         = this,
      clearInterval = app.win.clearInterval,
      config        = app.config.text,
      context       = app.buffer.context,
      doc           = app.doc,
      gui           = app.gui,
      image         = app.image,
      lang          = app.lang,
      MathRound     = Math.round,
      mouse         = app.mouse,
      setInterval   = app.win.setInterval;

  /**
   * The interval ID used for invoking the drawing operation every few 
   * milliseconds.
   *
   * @private
   * @see PaintWeb.config.toolDrawDelay
   */
  var timer = null;

  /**
   * Holds the previous tool ID.
   *
   * @private
   * @type String
   */
  var prevTool = app.tool ? app.tool._id : null;

  /**
   * Tells if the drawing canvas needs to be updated or not.
   *
   * @private
   * @type Boolean
   * @default false
   */
  var needsRedraw = false;

  var inputString = null,
      input_fontFamily = null,
      ev_configChangeId = null,
      ns_svg = "http://www.w3.org/2000/svg",
      svgDoc = null,
      svgText = null,
      textWidth = 0,
      textHeight = 0;

  /**
   * Tool preactivation code. This method check if the browser has support for 
   * rendering text in Canvas.
   *
   * @returns {Boolean} True if the tool can be activated successfully, or false 
   * if not.
   */
  this.preActivate = function () {
    if (!gui.inputs.textString || !gui.inputs.text_fontFamily || 
        !gui.elems.viewport) {
      return false;

    }

    // Canvas 2D Text API
    if (context.fillText && context.strokeText) {
      return true;
    }

    // Opera can only render text via SVG Text.
    // Note: support for Opera has been disabled.
    // There are severe SVG redraw issues when updating the SVG text element.
    // Besides, there are important memory leaks.
    // Ultimately, there's a deal breaker: security violation. The SVG document 
    // which is rendered inside Canvas is considered "external" 
    // - get/putImageData() and toDataURL() stop working after drawImage(svg) is 
    // invoked. Eh.
    /*if (pwlib.browser.opera) {
      return true;
    }*/

    // Gecko 1.9.0 had its own proprietary Canvas 2D Text API.
    if (context.mozPathText) {
      return true;
    }

    alert(lang.errorTextUnsupported);
    return false;
  };

  /**
   * The tool activation code. This sets up a few variables, starts the drawing 
   * timer and adds event listeners as needed.
   */
  this.activate = function () {
    // Reset the mouse coordinates to the scroll top/left corner such that the 
    // text is rendered there.
    mouse.x = Math.round(gui.elems.viewport.scrollLeft / image.canvasScale),
    mouse.y = Math.round(gui.elems.viewport.scrollTop  / image.canvasScale),

    input_fontFamily = gui.inputs.text_fontFamily;
    inputString = gui.inputs.textString;

    if (!context.fillText && pwlib.browser.opera) {
      ev_configChangeId = app.events.add('configChange', ev_configChange_opera);
      inputString.addEventListener('input',  ev_configChange_opera, false);
      inputString.addEventListener('change', ev_configChange_opera, false);
    } else {
      ev_configChangeId = app.events.add('configChange', ev_configChange);
      inputString.addEventListener('input',  ev_configChange, false);
      inputString.addEventListener('change', ev_configChange, false);
    }

    // Render text using the Canvas 2D context text API defined by HTML 5.
    if (context.fillText && context.strokeText) {
      _self.draw = _self.draw_spec;

    } else if (pwlib.browser.opera) {
      // Render text using a SVG Text element which is copied into Canvas using 
      // drawImage().
      _self.draw = _self.draw_opera;
      initOpera();

    } else if (context.mozPathText) {
      // Render text using proprietary API available in Gecko 1.9.0.
      _self.draw = _self.draw_moz;
      textWidth = context.mozMeasureText(inputString.value);
    }

    if (!timer) {
      timer = setInterval(_self.draw, app.config.toolDrawDelay);
    }
    needsRedraw = true;
  };

  /**
   * The tool deactivation simply consists of removing the event listeners added 
   * when the tool was constructed, and clearing the buffer canvas.
   */
  this.deactivate = function () {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
    needsRedraw = false;

    if (ev_configChangeId) {
      app.events.remove('configChange', ev_configChangeId);
    }

    if (!context.fillText && pwlib.browser.opera) {
      inputString.removeEventListener('input',  ev_configChange_opera, false);
      inputString.removeEventListener('change', ev_configChange_opera, false);
    } else {
      inputString.removeEventListener('input',  ev_configChange, false);
      inputString.removeEventListener('change', ev_configChange, false);
    }

    svgText = null;
    svgDoc = null;

    context.clearRect(0, 0, image.width, image.height);

    return true;
  };

  /**
   * Initialize the SVG document for Opera. This is used for rendering the text.
   * @private
   */
  function initOpera () {
    svgDoc = doc.createElementNS(ns_svg, 'svg');
    svgDoc.setAttributeNS(ns_svg, 'version', '1.1');

    svgText = doc.createElementNS(ns_svg, 'text');
    svgText.appendChild(doc.createTextNode(inputString.value));
    svgDoc.appendChild(svgText);

    svgText.style.font = context.font;

    if (app.config.shapeType !== 'stroke') {
      svgText.style.fill = context.fillStyle;
    } else {
      svgText.style.fill = 'none';
    }

    if (app.config.shapeType !== 'fill') {
      svgText.style.stroke = context.strokeStyle;
      svgText.style.strokeWidth = context.lineWidth;
    } else {
      svgText.style.stroke = 'none';
      svgText.style.strokeWidth = context.lineWidth;
    }

    textWidth  = svgText.getComputedTextLength();
    textHeight = svgText.getBBox().height;

    svgDoc.setAttributeNS(ns_svg, 'width',  textWidth);
    svgDoc.setAttributeNS(ns_svg, 'height', textHeight + 10);
    svgText.setAttributeNS(ns_svg, 'x', 0);
    svgText.setAttributeNS(ns_svg, 'y', textHeight);
  };

  /**
   * The <code>configChange</code> application event handler. This is also the 
   * <code>input</code> and <code>change</code> event handler for the text 
   * string input element.  This method updates the Canvas text-related 
   * properties as needed, and re-renders the text.
   *
   * <p>This function is not used on Opera.
   *
   * @param {Event|pwlib.appEvent.configChange} ev The application/DOM event 
   * object.
   */
  function ev_configChange (ev) {
    if (ev.type === 'input' || ev.type === 'change' ||
        (!ev.group && ev.config === 'shapeType') ||
        (ev.group === 'line' && ev.config === 'lineWidth')) {
      needsRedraw = true;

      // Update the text width.
      if (!context.fillText && context.mozMeasureText) {
        textWidth = context.mozMeasureText(inputString.value);
      }
      return;
    }

    if (ev.type !== 'configChange' && ev.group !== 'text') {
      return;
    }

    var font = '';

    switch (ev.config) {
      case 'fontFamily':
        if (ev.value === '+') {
          fontFamilyAdd(ev);
        }
      case 'bold':
      case 'italic':
      case 'fontSize':
        if (config.bold) {
          font += 'bold ';
        }
        if (config.italic) {
          font += 'italic ';
        }
        font += config.fontSize + 'px ' + config.fontFamily;
        context.font = font;

        if ('mozTextStyle' in context) {
          context.mozTextStyle = font;
        }

      case 'textAlign':
      case 'textBaseline':
        needsRedraw = true;
    }

    // Update the text width.
    if (ev.config !== 'textAlign' && ev.config !== 'textBaseline' && 
        !context.fillText && context.mozMeasureText) {
      textWidth = context.mozMeasureText(inputString.value);
    }
  };

  /**
   * The <code>configChange</code> application event handler. This is also the 
   * <code>input</code> and <code>change</code> event handler for the text 
   * string input element.  This method updates the Canvas text-related 
   * properties as needed, and re-renders the text.
   *
   * <p>This is function is specific to Opera.
   *
   * @param {Event|pwlib.appEvent.configChange} ev The application/DOM event 
   * object.
   */
  function ev_configChange_opera (ev) {
    if (ev.type === 'input' || ev.type === 'change') {
      svgText.replaceChild(doc.createTextNode(this.value), svgText.firstChild);
      needsRedraw = true;
    }

    if (!ev.group && ev.config === 'shapeType') {
      if (ev.value !== 'stroke') {
        svgText.style.fill = context.fillStyle;
      } else {
        svgText.style.fill = 'none';
      }

      if (ev.value !== 'fill') {
        svgText.style.stroke = context.strokeStyle;
        svgText.style.strokeWidth = context.lineWidth;
      } else {
        svgText.style.stroke = 'none';
        svgText.style.strokeWidth = context.lineWidth;
      }
      needsRedraw = true;
    }

    if (!ev.group && ev.config === 'fillStyle') {
      if (app.config.shapeType !== 'stroke') {
        svgText.style.fill = ev.value;
        needsRedraw = true;
      }
    }

    if ((!ev.group && ev.config === 'strokeStyle') ||
        (ev.group === 'line' && ev.config === 'lineWidth')) {
      if (app.config.shapeType !== 'fill') {
        svgText.style.stroke = context.strokeStyle;
        svgText.style.strokeWidth = context.lineWidth;
        needsRedraw = true;
      }
    }

    if (ev.type === 'configChange' && ev.group === 'text') {
      var font = '';
      switch (ev.config) {
        case 'fontFamily':
          if (ev.value === '+') {
            fontFamilyAdd(ev);
          }
        case 'bold':
        case 'italic':
        case 'fontSize':
          if (config.bold) {
            font += 'bold ';
          }
          if (config.italic) {
            font += 'italic ';
          }
          font += config.fontSize + 'px ' + config.fontFamily;
          context.font = font;
          svgText.style.font = font;

        case 'textAlign':
        case 'textBaseline':
          needsRedraw = true;
      }
    }

    textWidth  = svgText.getComputedTextLength();
    textHeight = svgText.getBBox().height;

    svgDoc.setAttributeNS(ns_svg, 'width',  textWidth);
    svgDoc.setAttributeNS(ns_svg, 'height', textHeight + 10);
    svgText.setAttributeNS(ns_svg, 'x', 0);
    svgText.setAttributeNS(ns_svg, 'y', textHeight);
  };

  /**
   * Add a new font family into the font family drop down. This function is 
   * invoked by the <code>ev_configChange()</code> function when the user 
   * attempts to add a new font family.
   *
   * @private
   *
   * @param {pwlib.appEvent.configChange} ev The application event object.
   */
  function fontFamilyAdd (ev) {
    var new_font = prompt(lang.promptTextFont) || '';
    new_font = new_font.replace(/^\s+/, '').replace(/\s+$/, '') || 
      ev.previousValue;

    // Check if the font name is already in the list.
    var opt, new_font2 = new_font.toLowerCase(),
        n = input_fontFamily.options.length;

    for (var i = 0; i < n; i++) {
      opt = input_fontFamily.options[i];
      if (opt.value.toLowerCase() == new_font2) {
        config.fontFamily = opt.value;
        input_fontFamily.selectedIndex = i;
        input_fontFamily.value = config.fontFamily;
        ev.value = config.fontFamily;

        return;
      }
    }

    // Add the new font.
    opt = doc.createElement('option');
    opt.value = new_font;
    opt.appendChild(doc.createTextNode(new_font));
    input_fontFamily.insertBefore(opt, input_fontFamily.options[n-1]);
    input_fontFamily.selectedIndex = n-1;
    input_fontFamily.value = new_font;
    ev.value = new_font;
    config.fontFamily = new_font;
  };

  /**
   * The <code>mousemove</code> event handler.
   */
  this.mousemove = function () {
    needsRedraw = true;
  };

  /**
   * Perform the drawing operation using standard 2D context methods.
   *
   * @see PaintWeb.config.toolDrawDelay
   */
  this.draw_spec = function () {
    if (!needsRedraw) {
      return;
    }

    context.clearRect(0, 0, image.width, image.height);

    if (app.config.shapeType != 'stroke') {
      context.fillText(inputString.value, mouse.x, mouse.y);
    }

    if (app.config.shapeType != 'fill') {
      context.beginPath();
      context.strokeText(inputString.value, mouse.x, mouse.y);
      context.closePath();
    }

    needsRedraw = false;
  };

  /**
   * Perform the drawing operation in Gecko 1.9.0.
   */
  this.draw_moz = function () {
    if (!needsRedraw) {
      return;
    }

    context.clearRect(0, 0, image.width, image.height);

    var x = mouse.x,
        y = mouse.y;

    if (config.textAlign === 'center') {
      x -= MathRound(textWidth / 2);
    } else if (config.textAlign === 'right') {
      x -= textWidth;
    }

    if (config.textBaseline === 'top') {
      y += config.fontSize;
    } else if (config.textBaseline === 'middle') {
      y += MathRound(config.fontSize / 2);
    }

    context.setTransform(1, 0, 0, 1, x, y);
    context.beginPath();
    context.mozPathText(inputString.value);

    if (app.config.shapeType != 'stroke') {
      context.fill();
    }

    if (app.config.shapeType != 'fill') {
      context.stroke();
    }
    context.closePath();
    context.setTransform(1, 0, 0, 1, 0, 0);

    needsRedraw = false;
  };

  /**
   * Perform the drawing operation in Opera using SVG.
   */
  this.draw_opera = function () {
    if (!needsRedraw) {
      return;
    }

    context.clearRect(0, 0, image.width, image.height);

    var x = mouse.x,
        y = mouse.y;

    if (config.textAlign === 'center') {
      x -= MathRound(textWidth / 2);
    } else if (config.textAlign === 'right') {
      x -= textWidth;
    }

    if (config.textBaseline === 'bottom') {
      y -= textHeight;
    } else if (config.textBaseline === 'middle') {
      y -= MathRound(textHeight / 2);
    }

    context.drawImage(svgDoc, x, y);

    needsRedraw = false;
  };

  /**
   * The <code>click</code> event handler. This method completes the drawing 
   * operation by inserting the text into the layer canvas.
   */
  this.click = function () {
    _self.draw();
    app.layerUpdate();
  };

  /**
   * The <code>keydown</code> event handler allows users to press the 
   * <kbd>Escape</kbd> key to cancel the drawing operation and return to the 
   * previous tool.
   *
   * @param {Event} ev The DOM Event object.
   * @returns {Boolean} True if the key was recognized, or false if not.
   */
  this.keydown = function (ev) {
    if (!prevTool || ev.kid_ != 'Escape') {
      return false;
    }

    mouse.buttonDown = false;
    app.toolActivate(prevTool, ev);

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-07-09 14:26:21 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the implementation of the Color Mixer dialog.
 */

// For the implementation of this extension I used the following references:
// - Wikipedia articles on each subject.
// - the great brucelindbloom.com Web site - lots of information.

/**
 * @class The Color Mixer extension.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.extensions.colormixer = function (app) {
  var _self     = this,
      config    = app.config.colormixer,
      doc       = app.doc,
      gui       = app.gui,
      lang      = app.lang.colormixer,
      MathFloor = Math.floor,
      MathMax   = Math.max,
      MathMin   = Math.min,
      MathPow   = Math.pow,
      MathRound = Math.round,
      resScale  = app.resolution.scale;

  /**
   * Holds references to various DOM elements.
   *
   * @private
   * @type Object
   */
  this.elems = {
    /**
     * Reference to the element which holds Canvas controls (the dot on the 
     * Canvas, and the slider).
     * @type Element
     */
    'controls': null,

    /**
     * Reference to the dot element that is rendered on top of the color space 
     * visualisation.
     * @type Element
     */
    'chartDot': null,

    /**
     * Reference to the slider element.
     * @type Element
     */
    'slider': null,

    /**
     * Reference to the input element that allows the user to pick the color 
     * palette to be displayed.
     * @type Element
     */
    'cpaletteInput': null,

    /**
     * The container element which holds the colors of the currently selected 
     * palette.
     * @type Element
     */
    'cpaletteOutput': null,

    /**
     * Reference to the element which displays the current color.
     * @type Element
     */
    "colorActive": null,

    /**
     * Reference to the element which displays the old color.
     * @type Element
     */
    "colorOld": null
  };

  /**
   * Reference to the Color Mixer floating panel GUI component object.
   *
   * @private
   * @type pwlib.guiFloatingPanel
   */
  this.panel = null;

  /**
   * Reference to the Color Mixer tab panel GUI component object which holds the 
   * inputs.
   *
   * @private
   * @type pwlib.guiTabPanel
   */
  this.panelInputs = null;

  /**
   * Reference to the Color Mixer tab panel GUI component object which holds the 
   * Canvas used for color space visualisation and the color palettes selector.
   *
   * @private
   * @type pwlib.guiTabPanel
   */
  this.panelSelector = null;

  /**
   * Holds a reference to the 2D context of the color mixer Canvas element. This 
   * is where the color chart and the slider are both drawn.
   *
   * @private
   * @type CanvasRenderingContext2D
   */
  this.context2d = false;

  /**
   * Target input hooks. This object must hold two methods:
   *
   * <ul>
   *   <li><code>show()</code> which is invoked by this extension when the Color 
   *   Mixer panel shows up on screen.
   *
   *   <li><code>hide()</code> which is invoked when the Color Mixer panel is 
   *   hidden from the screen.
   * </ul>
   *
   * <p>The object must also hold information about the associated configuration 
   * property: <var>configProperty</var>, <var>configGroup</var> and 
   * <var>configGroupRef</var>.
   *
   * @type Object
   */
  this.targetInput = null;

  /**
   * Holds the current color in several formats: RGB, HEX, HSV, CIE Lab, and 
   * CMYK. Except for 'hex', all the values should be from 0 to 1.
   *
   * @type Object
   */
  this.color = {
    // RGB
    red  : 0,
    green: 0,
    blue : 0,

    alpha : 0,
    hex   : 0,

    // HSV
    hue : 0,
    sat : 0,
    val : 0,

    // CMYK
    cyan    : 0,
    magenta : 0,
    yellow  : 0,
    black   : 0,

    // CIE Lab
    cie_l : 0,
    cie_a : 0,
    cie_b : 0
  };

  /**
   * Holds references to all the DOM input fields, for each color channel.
   *
   * @private
   * @type Object
   */
  this.inputs = {
    red   : null,
    green : null,
    blue  : null,

    alpha : null,
    hex   : null,

    hue : null,
    sat : null,
    val : null,

    cyan    : null,
    magenta : null,
    yellow  : null,
    black   : null,

    cie_l : null,
    cie_a : null,
    cie_b : null
  };

  /**
   * The "absolute maximum" value is determined based on the min/max values.  
   * For min -100 and max 100, the abs_max is 200.
   * @private
   *
   */
  this.abs_max  = {};

  // The hue spectrum used by the HSV charts.
  var hueSpectrum = [
    [255,   0,   0], // 0, Red,       0°
    [255, 255,   0], // 1, Yellow,   60°
    [  0, 255,   0], // 2, Green,   120°
    [  0, 255, 255], // 3, Cyan,    180°
    [  0,   0, 255], // 4, Blue,    240°
    [255,   0, 255], // 5, Magenta, 300°
    [255,   0,   0]  // 6, Red,     360°
  ];

  // The active color key (input) determines how the color chart works.
  this.ckey_active = 'red';

  // Given a group of the inputs: red, green and blue, when one of them is active, the ckey_adjoint is set to an array of the other two input IDs.
  this.ckey_adjoint = false;
  this.ckey_active_group = false;

  this.ckey_grouping = {
    'red'   : 'rgb',
    'green' : 'rgb',
    'blue'  : 'rgb',

    'hue' : 'hsv',
    'sat' : 'hsv',
    'val' : 'hsv',

    'cyan'    : 'cmyk',
    'magenta' : 'cmyk',
    'yellow'  : 'cmyk',
    'black'   : 'cmyk',

    'cie_l' : 'lab',
    'cie_a' : 'lab',
    'cie_b' : 'lab'
  };

  // These values are automatically calculated when the color mixer is 
  // initialized.
  this.sliderX = 0;
  this.sliderWidth = 0;
  this.sliderHeight = 0;
  this.sliderSpacing = 0;
  this.chartWidth = 0;
  this.chartHeight = 0;

  /**
   * Register the Color Mixer extension.
   *
   * @returns {Boolean} True if the extension can be registered properly, or 
   * false if not.
   */
  this.extensionRegister = function (ev) {
    if (!gui.elems || !gui.elems.colormixer_canvas || !gui.floatingPanels || 
        !gui.floatingPanels.colormixer || !gui.tabPanels || 
        !gui.tabPanels.colormixer_inputs || !gui.tabPanels.colormixer_selector 
        || !_self.init_lab()) {
      return false;
    }

    _self.panel = gui.floatingPanels.colormixer;
    _self.panelSelector = gui.tabPanels.colormixer_selector;
    _self.panelInputs = gui.tabPanels.colormixer_inputs;

    // Initialize the color mixer Canvas element.
    _self.context2d = gui.elems.colormixer_canvas.getContext('2d');
    if (!_self.context2d) {
      return false;
    }

    // Setup the color mixer inputs.
    var elem, label, labelElem,
        inputValues = config.inputValues,
        form = _self.panelInputs.container;
    if (!form) {
      return false;
    }

    for (var i in _self.inputs) {
      elem = form.elements.namedItem('ckey_' + i) || gui.inputs['ckey_' + i];
      if (!elem) {
        return false;
      }

      if (i === 'hex' || i === 'alpha') {
        label = lang.inputs[i];
      } else {
        label = lang.inputs[_self.ckey_grouping[i] + '_' + i];
      }

      labelElem = elem.parentNode;
      labelElem.replaceChild(doc.createTextNode(label), labelElem.firstChild);

      elem.addEventListener('input',  _self.ev_input_change, false);
      elem.addEventListener('change', _self.ev_input_change, false);

      if (i !== 'hex') {
        elem.setAttribute('step', inputValues[i][2]);

        elem.setAttribute('max', MathRound(inputValues[i][1]));
        elem.setAttribute('min', MathRound(inputValues[i][0]));
        _self.abs_max[i] = inputValues[i][1] - inputValues[i][0];
      }

      // Store the color key, which is used by the event handler.
      elem._ckey = i;
      _self.inputs[i] = elem;
    }

    // Setup the ckey inputs of type=radio.
    var ckey = form.ckey;
    if (!ckey) {
      return false;
    }
    for (var i = 0, n = ckey.length; i < n; i++) {
      elem = ckey[i];
      if (_self.ckey_grouping[elem.value] === 'lab' && 
          !_self.context2d.putImageData) {
        elem.disabled = true;
        continue;
      }

      elem.addEventListener('change', _self.ev_change_ckey_active, false);

      if (elem.value === _self.ckey_active) {
        elem.checked = true;
        _self.update_ckey_active(_self.ckey_active, true);
      }
    }

    // Prepare the color preview elements.
    _self.elems.colorActive = gui.elems.colormixer_colorActive.firstChild;
    _self.elems.colorOld = gui.elems.colormixer_colorOld.firstChild;
    _self.elems.colorOld.addEventListener('click', _self.ev_click_color, false);

    // Make sure the buttons work properly.
    var anchor, btn, buttons = ['accept', 'cancel', 'saveColor', 'pickColor'];
    for (var i = 0, n = buttons.length; i < n; i++) {
      btn = gui.elems['colormixer_btn_' + buttons[i]];
      if (!btn) {
        continue;
      }

      anchor = doc.createElement('a');
      anchor.href = '#';
      anchor.appendChild(doc.createTextNode(lang.buttons[buttons[i]]));
      anchor.addEventListener('click', _self['ev_click_' + buttons[i]], false);

      btn.replaceChild(anchor, btn.firstChild);
    }

    // Prepare the canvas "controls" (the chart "dot" and the slider).
    var id, elems = ['controls', 'chartDot', 'slider'];
    for (var i = 0, n = elems.length; i < n; i++) {
      id = elems[i];
      elem = gui.elems['colormixer_' + id];
      if (!elem) {
        return false;
      }

      elem.addEventListener('mousedown', _self.ev_canvas, false);
      elem.addEventListener('mousemove', _self.ev_canvas, false);
      elem.addEventListener('mouseup',   _self.ev_canvas, false);

      _self.elems[id] = elem;
    }

    // The color palette <select>.
    _self.elems.cpaletteInput = gui.inputs.colormixer_cpaletteInput;
    _self.elems.cpaletteInput.addEventListener('change', 
        _self.ev_change_cpalette, false);

    // Add the list of color palettes into the <select>.
    var palette;
    for (var i in config.colorPalettes) {
      palette = config.colorPalettes[i];
      elem = doc.createElement('option');
      elem.value = i;
      if (i === config.paletteDefault) {
        elem.selected = true;
      }

      elem.appendChild( doc.createTextNode(lang.colorPalettes[i]) );
      _self.elems.cpaletteInput.appendChild(elem);
    }

    // This is the ordered list where we add each color (list item).
    _self.elems.cpaletteOutput = gui.elems.colormixer_cpaletteOutput;
    _self.elems.cpaletteOutput.addEventListener('click', _self.ev_click_color, 
        false);

    _self.cpalette_load(config.paletteDefault);

    // Make sure the Canvas element scale is in sync with the application.
    app.events.add('canvasSizeChange', _self.update_dimensions);

    _self.panelSelector.events.add('guiTabActivate', _self.ev_tabActivate);

    // Make sure the Color Mixer is properly closed when the floating panel is 
    // closed.
    _self.panel.events.add('guiFloatingPanelStateChange', 
        _self.ev_panel_stateChange);

    return true;
  };

  /**
   * This function calculates lots of values used by the other CIE Lab-related 
   * functions.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.init_lab = function () {
    var cfg = config.lab;
    if (!cfg) {
      return false;
    }

    // Chromaticity coordinates for the RGB primaries.
    var x0_r = cfg.x_r,
        y0_r = cfg.y_r,
        x0_g = cfg.x_g,
        y0_g = cfg.y_g,
        x0_b = cfg.x_b,
        y0_b = cfg.y_b,

        // The reference white point (xyY to XYZ).
        w_x = cfg.ref_x / cfg.ref_y,
        w_y = 1,
        w_z = (1 - cfg.ref_x - cfg.ref_y) / cfg.ref_y;

    cfg.w_x = w_x;
    cfg.w_y = w_y;
    cfg.w_z = w_z;

    // Again, xyY to XYZ for each RGB primary. Y=1.
    var x_r = x0_r / y0_r,
        y_r = 1,
        z_r = (1 - x0_r - y0_r) / y0_r,
        x_g = x0_g / y0_g,
        y_g = 1,
        z_g = (1 - x0_g - y0_g) / y0_g,
        x_b = x0_b / y0_b,
        y_b = 1,
        z_b = (1 - x0_b - y0_b) / y0_b,
        m   = [x_r, y_r, z_r,
               x_g, y_g, z_g,
               x_b, y_b, z_b],
        m_i = _self.calc_m3inv(m),
        s   = _self.calc_m1x3([w_x, w_y, w_z], m_i);

    // The 3x3 matrix used by rgb2xyz().
    m = [s[0] * m[0], s[0] * m[1], s[0] * m[2],
         s[1] * m[3], s[1] * m[4], s[1] * m[5],
         s[2] * m[6], s[2] * m[7], s[2] * m[8]];

    // The matrix inverse, used by xyz2rgb();
    cfg.m_i = _self.calc_m3inv(m);
    cfg.m   = m;

    // Now determine the min/max values for a and b.

    var xyz = _self.rgb2xyz([0, 1, 0]), // green gives the minimum value for a
        lab = _self.xyz2lab(xyz),
        values = config.inputValues;
    values.cie_a[0] = lab[1];

    xyz = _self.rgb2xyz([1, 0, 1]);     // magenta gives the maximum value for a
    lab = _self.xyz2lab(xyz);
    values.cie_a[1] = lab[1];

    xyz = _self.rgb2xyz([0, 0, 1]);     // blue gives the minimum value for b
    lab = _self.xyz2lab(xyz);
    values.cie_b[0] = lab[2];

    xyz = _self.rgb2xyz([1, 1, 0]);     // yellow gives the maximum value for b
    lab = _self.xyz2lab(xyz);
    values.cie_b[1] = lab[2];

    return true;
  };

  /**
   * The <code>guiTabActivate</code> event handler for the tab panel which holds 
   * the color mixer and the color palettes. When switching back to the color 
   * mixer, this method updates the Canvas.
   *
   * @private
   * @param {pwlib.appEvent.guiTabActivate} ev The application event object.
   */
  this.ev_tabActivate = function (ev) {
    if (ev.tabId === 'mixer' && _self.update_canvas_needed) {
      _self.update_canvas(null, true);
    }
  };

  /**
   * The <code>click</code> event handler for the Accept button. This method 
   * dispatches the {@link pwlib.appEvent.configChange} application event for 
   * the configuration property associated to the target input, and hides the 
   * Color Mixer floating panel.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.ev_click_accept = function (ev) {
    ev.preventDefault();

    var configProperty = _self.targetInput.configProperty,
        configGroup    = _self.targetInput.configGroup,
        configGroupRef = _self.targetInput.configGroupRef,
        prevVal = configGroupRef[configProperty],
        newVal  = 'rgba(' + MathRound(_self.color.red   * 255) + ',' +
                            MathRound(_self.color.green * 255) + ',' +
                            MathRound(_self.color.blue  * 255) + ',' +
                            _self.color.alpha + ')';

    _self.hide();

    if (prevVal !== newVal) {
      configGroupRef[configProperty] = newVal;
      app.events.dispatch(new pwlib.appEvent.configChange(newVal, prevVal, 
          configProperty, configGroup, configGroupRef));
    }
  };

  /**
   * The <code>click</code> event handler for the Cancel button. This method 
   * hides the Color Mixer floating panel.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.ev_click_cancel = function (ev) {
    ev.preventDefault();
    _self.hide();
  };

  /**
   * The <code>click</code> event handler for the "Save color" button. This 
   * method adds the current color into the "_saved" color palette.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  // TODO: provide a way to save the color palette permanently. This should use 
  // some application event.
  this.ev_click_saveColor = function (ev) {
    ev.preventDefault();

    var color = [_self.color.red, _self.color.green, _self.color.blue],
        saved = config.colorPalettes._saved;

    saved.colors.push(color);

    _self.elems.cpaletteInput.value = '_saved';
    _self.cpalette_load('_saved');
    _self.panelSelector.tabActivate('cpalettes');

    return true;
  };

  /**
   * The <code>click</code> event handler for the "Pick color" button. This 
   * method activates the color picker tool.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.ev_click_pickColor = function (ev) {
    ev.preventDefault();
    app.toolActivate('cpicker', ev);
  };

  /**
   * The <code>change</code> event handler for the color palette input element.  
   * This loads the color palette the user selected.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.ev_change_cpalette = function (ev) {
    _self.cpalette_load(this.value);
  };

  /**
   * Load a color palette. Loading is performed asynchronously.
   *
   * @param {String} id The color palette ID.
   *
   * @returns {Boolean} True if the load was successful, or false if not.
   */
  this.cpalette_load = function (id) {
    if (!id || !(id in config.colorPalettes)) {
      return false;
    }

    var palette = config.colorPalettes[id];

    if (palette.file) {
      pwlib.xhrLoad(PaintWeb.baseFolder + palette.file, this.cpalette_loaded);

      return true;

    } else if (palette.colors) {
      return this.cpalette_show(palette.colors);

    } else {
      return false;
    }
  };

  /**
   * The <code>onreadystatechange</code> event handler for the color palette 
   * XMLHttpRequest object.
   *
   * @private
   * @param {XMLHttpRequest} xhr The XMLHttpRequest object.
   */
  this.cpalette_loaded = function (xhr) {
    if (!xhr || xhr.readyState !== 4) {
      return;
    }

    if ((xhr.status !== 304 && xhr.status !== 200) || !xhr.responseText) {
      alert(lang.failedColorPaletteLoad);
      return;
    }

    var colors = JSON.parse(xhr.responseText);
    xhr = null;
    _self.cpalette_show(colors);
  };

  /**
   * Show a color palette. This method adds all the colors in the DOM as 
   * individual anchor elements which users can click on.
   *
   * @private
   *
   * @param {Array} colors The array which holds each color in the palette.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.cpalette_show = function (colors) {
    if (!colors || !(colors instanceof Array)) {
      return false;
    }

    var color, anchor, rgbValue,
        frag = doc.createDocumentFragment(),
        dest = this.elems.cpaletteOutput;

    dest.style.display = 'none';
    while (dest.hasChildNodes()) {
      dest.removeChild(dest.firstChild);
    }

    for (var i = 0, n = colors.length; i < n; i++) {
      color = colors[i];

      // Do not allow values higher than 1.
      color[0] = MathMin(1, color[0]);
      color[1] = MathMin(1, color[1]);
      color[2] = MathMin(1, color[2]);

      rgbValue = 'rgb(' + MathRound(color[0] * 255) + ',' +
          MathRound(color[1] * 255) + ',' +
          MathRound(color[2] * 255) + ')';

      anchor = doc.createElement('a');
      anchor.href = '#';
      anchor._color = color;
      anchor.style.backgroundColor = rgbValue;
      anchor.appendChild(doc.createTextNode(rgbValue));

      frag.appendChild(anchor);
    }

    dest.appendChild(frag);
    dest.style.display = 'block';

    colors = frag = null;

    return true;
  };

  /**
   * The <code>click</code> event handler for colors in the color palette list.  
   * This event handler is also used for the "old color" element. This method 
   * updates the color mixer to use the color the user picked.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.ev_click_color = function (ev) {
    var color = ev.target._color;
    if (!color) {
      return;
    }

    ev.preventDefault();

    _self.color.red   = color[0];
    _self.color.green = color[1];
    _self.color.blue  = color[2];

    if (typeof(color[3]) !== 'undefined') {
      _self.color.alpha = color[3];
    }

    _self.update_color('rgb');
  };

  /**
   * Recalculate the dimensions and coordinates for the slider and for the color 
   * space visualisation within the Canvas element.
   *
   * <p>This method is an event handler for the {@link 
   * pwlib.appEvent.canvasSizeChange} application event.
   *
   * @private
   */
  this.update_dimensions = function () {
    if (resScale === app.resolution.scale) {
      return;
    }

    resScale = app.resolution.scale;

    var canvas  = _self.context2d.canvas,
        width   = canvas.width,
        height  = canvas.height,
        sWidth  = width  / resScale,
        sHeight = height / resScale,
        style;

    _self.sliderWidth   = MathRound(width * config.sliderWidth);
    _self.sliderHeight  = height - 1;
    _self.sliderSpacing = MathRound(width * config.sliderSpacing);
    _self.sliderX       = width - _self.sliderWidth - 2;
    _self.chartWidth    = _self.sliderX - _self.sliderSpacing;
    _self.chartHeight   = height;

    style = _self.elems.controls.style;
    style.width  = sWidth  + 'px';
    style.height = sHeight + 'px';

    style = _self.elems.slider.style;
    style.width = (_self.sliderWidth / resScale) + 'px';
    style.left  = (_self.sliderX     / resScale) + 'px';

    style = canvas.style;
    style.width  = sWidth  + 'px';
    style.height = sHeight + 'px';

    if (_self.panel.state !== _self.panel.STATE_HIDDEN) {
      _self.update_canvas();
    }
  };

  /**
   * Calculate the product of two matrices.
   *
   * <p>Matrices are one-dimensional arrays of the form <code>[a0, a1, a2, ..., 
   * b0, b1, b2, ...]</code> where each element from the matrix is given in 
   * order, from the left to the right, row by row from the top to the bottom.
   *
   * @param {Array} a The first matrix must be one row and three columns.
   * @param {Array} b The second matrix must be three rows and three columns.
   *
   * @returns {Array} The matrix product, one row and three columns.
   */
  // Note: for obvious reasons, this method is not a full-fledged matrix product 
  // calculator. It's as simple as possible - fitting only the very specific 
  // needs of the color mixer.
  this.calc_m1x3 = function (a, b) {
    if (!(a instanceof Array) || !(b instanceof Array)) {
      return false;
    } else {
      return [
        a[0] * b[0] + a[1] * b[3] + a[2] * b[6], // x
        a[0] * b[1] + a[1] * b[4] + a[2] * b[7], // y
        a[0] * b[2] + a[1] * b[5] + a[2] * b[8]  // z
      ];
    }
  };

  /**
   * Calculate the matrix inverse.
   *
   * <p>Matrices are one-dimensional arrays of the form <code>[a0, a1, a2, ..., 
   * b0, b1, b2, ...]</code> where each element from the matrix is given in 
   * order, from the left to the right, row by row from the top to the bottom.
   *
   * @private
   *
   * @param {Array} m The square matrix which must have three rows and three 
   * columns.
   *
   * @returns {Array|false} The computed matrix inverse, or false if the matrix 
   * determinant was 0 - the given matrix is not invertible.
   */
  // Note: for obvious reasons, this method is not a full-fledged matrix inverse 
  // calculator. It's as simple as possible - fitting only the very specific 
  // needs of the color mixer.
  this.calc_m3inv = function (m) {
    if (!(m instanceof Array)) {
      return false;
    }

    var d = (m[0]*m[4]*m[8] + m[1]*m[5]*m[6] + m[2]*m[3]*m[7]) -
            (m[2]*m[4]*m[6] + m[5]*m[7]*m[0] + m[8]*m[1]*m[3]);

    // Matrix determinant is 0: the matrix is not invertible.
    if (d === 0) {
      return false;
    }

    var i = [
       m[4]*m[8] - m[5]*m[7], -m[3]*m[8] + m[5]*m[6],  m[3]*m[7] - m[4]*m[6],
      -m[1]*m[8] + m[2]*m[7],  m[0]*m[8] - m[2]*m[6], -m[0]*m[7] + m[1]*m[6],
       m[1]*m[5] - m[2]*m[4], -m[0]*m[5] + m[2]*m[3],  m[0]*m[4] - m[1]*m[3]
    ];

    i = [1/d * i[0], 1/d * i[3], 1/d * i[6],
         1/d * i[1], 1/d * i[4], 1/d * i[7],
         1/d * i[2], 1/d * i[5], 1/d * i[8]];

    return i;
  };

  /**
   * The <code>change</code> event handler for the Color Mixer inputs of 
   * type=radio. This method allows users to change the active color key - used 
   * for the color space visualisation.
   * @private
   */
  this.ev_change_ckey_active = function () {
    if (this.value && this.value !== _self.ckey_active) {
      _self.update_ckey_active(this.value);
    }
  };

  /**
   * Update the active color key. This method updates the Canvas accordingly.
   *
   * @private
   *
   * @param {String} ckey The color key you want to be active.
   * @param {Boolean} [only_vars] Tells if you want only the variables to be 
   * updated - no Canvas updates. This is true only during the Color Mixer 
   * initialization.
   *
   * @return {Boolean} True if the operation was successful, or false if not.
   */
  this.update_ckey_active = function (ckey, only_vars) {
    if (!_self.inputs[ckey]) {
      return false;
    }

    _self.ckey_active = ckey;

    var  adjoint = [], group = _self.ckey_grouping[ckey];

    // Determine the adjoint color keys. For example, if red is active, then adjoint = ['green', 'blue'].
    for (var i in _self.ckey_grouping) {
      if (_self.ckey_grouping[i] === group && i !== ckey) {
        adjoint.push(i);
      }
    }

    _self.ckey_active_group  = group;
    _self.ckey_adjoint       = adjoint;

    if (!only_vars) {
      if (_self.panelSelector.tabId !== 'mixer') {
        _self.update_canvas_needed = true;
        _self.panelSelector.tabActivate('mixer');
      } else {
        _self.update_canvas();
      }

      if (_self.panelInputs.tabId !== group) {
        _self.panelInputs.tabActivate(group);
      }
    }

    return true;
  };

  /**
   * Show the Color Mixer.
   *
   * @param {Object} target The target input object.
   *
   * @param {Object} color The color you want to set before the Color Mixer is 
   * shown. The object must have four properties: <var>red</var>, 
   * <var>green</var>, <var>blue</var> and <var>alpha</var>. All the values must 
   * be between 0 and 1. This color becomes the "active color" and the "old 
   * color".
   *
   * @see this.targetInput for more information about the <var>target</var> 
   * object.
   */
  this.show = function (target, color) {
      var styleActive = _self.elems.colorActive.style,
          colorOld    = _self.elems.colorOld,
          styleOld    = colorOld.style;

    if (target) {
      if (_self.targetInput) {
        _self.targetInput.hide();
      }

      _self.targetInput = target;
      _self.targetInput.show();
    }

    if (color) {
      _self.color.red   = color.red;
      _self.color.green = color.green;
      _self.color.blue  = color.blue;
      _self.color.alpha = color.alpha;

      _self.update_color('rgb');

      styleOld.backgroundColor = styleActive.backgroundColor;
      styleOld.opacity = styleActive.opacity;
      colorOld._color = [color.red, color.green, color.blue, color.alpha];
    }

    _self.panel.show();
  };

  /**
   * Hide the Color Mixer floating panel. This method invokes the 
   * <code>hide()</code> method provided by the target input.
   */
  this.hide = function () {
    _self.panel.hide();
    _self.ev_canvas_mode = false;
  };

  /**
   * The <code>guiFloatingPanelStateChange</code> event handler for the Color 
   * Mixer panel. This method ensures the Color Mixer is properly closed.
   *
   * @param {pwlib.appEvent.guiFloatingPanelStateChange} ev The application 
   * event object.
   */
  this.ev_panel_stateChange = function (ev) {
    if (ev.state === ev.STATE_HIDDEN) {
      if (_self.targetInput) {
        _self.targetInput.hide();
        _self.targetInput = null;
      }
      _self.ev_canvas_mode = false;
    }
  };

  /**
   * The <code>input</code> and <code>change</code> event handler for all the 
   * Color Mixer inputs.
   * @private
   */
  this.ev_input_change = function () {
    if (!this._ckey) {
      return;
    }

    // Validate and restrict the possible values.
    // If the input is unchanged, or if the new value is invalid, the function 
    // stops.
    // The hexadecimal input is checked with a simple regular expression.

    if ((this._ckey === 'hex' && !/^\#[a-f0-9]{6}$/i.test(this.value))) {
      return;
    }

    if (this.getAttribute('type') === 'number') {
      var val = parseInt(this.value),
          min = this.getAttribute('min'),
          max = this.getAttribute('max');

      if (isNaN(val)) {
        val = min;
      }

      if (val < min) {
        val = min;
      } else if (val > max) {
        val = max;
      }

      if (val != this.value) {
        this.value = val;
      }
    }

    // Update the internal color value.
    if (this._ckey === 'hex') {
      _self.color[this._ckey] = this.value;
    } else if (_self.ckey_grouping[this._ckey] === 'lab') {
      _self.color[this._ckey] = parseInt(this.value);
    } else {
      _self.color[this._ckey] = parseInt(this.value) 
        / config.inputValues[this._ckey][1];
    }

    _self.update_color(this._ckey);
  };

  /**
   * Update the current color. Once a color value is updated, this method is 
   * called to keep the rest of the color mixer in sync: for example, when a RGB 
   * value is updated, it needs to be converted to HSV, CMYK and all of the 
   * other formats. Additionally, this method updates the color preview, the 
   * controls on the Canvas and the input values.
   *
   * <p>You need to call this function whenever you update the color manually.
   *
   * @param {String} ckey The color key that was updated.
   */
  this.update_color = function (ckey) {
    var group = _self.ckey_grouping[ckey] || ckey;

    switch (group) {
      case 'rgb':
        _self.rgb2hsv();
        _self.rgb2hex();
        _self.rgb2lab();
        _self.rgb2cmyk();
        break;

      case 'hsv':
        _self.hsv2rgb();
        _self.rgb2hex();
        _self.rgb2lab();
        _self.rgb2cmyk();
        break;

      case 'hex':
        _self.hex2rgb();
        _self.rgb2hsv();
        _self.rgb2lab();
        _self.rgb2cmyk();
        break;

      case 'lab':
        _self.lab2rgb();
        _self.rgb2hsv();
        _self.rgb2hex();
        _self.rgb2cmyk();
        break;

      case 'cmyk':
        _self.cmyk2rgb();
        _self.rgb2lab();
        _self.rgb2hsv();
        _self.rgb2hex();
    }

    _self.update_preview();
    _self.update_inputs();

    if (ckey !== 'alpha') {
      _self.update_canvas(ckey);
    }
  };

  /**
   * Update the color preview.
   * @private
   */
  this.update_preview = function () {
    var red   = MathRound(_self.color.red   * 255),
        green = MathRound(_self.color.green * 255),
        blue  = MathRound(_self.color.blue  * 255),
        style = _self.elems.colorActive.style;

    style.backgroundColor = 'rgb(' + red + ',' + green + ',' + blue + ')';
    style.opacity = _self.color.alpha;
  };

  /**
   * Update the color inputs. This method takes the internal color values and 
   * shows them in the DOM input elements.
   * @private
   */
  this.update_inputs = function () {
    var input;
    for (var i in _self.inputs) {
      input = _self.inputs[i];
      input._old_value = input.value;
      if (input._ckey === 'hex') {
        input.value = _self.color[i];
      } else if (_self.ckey_grouping[input._ckey] === 'lab') {
        input.value = MathRound(_self.color[i]);
      } else {
        input.value = MathRound(_self.color[i] * config.inputValues[i][1]);
      }
    }
  };

  /**
   * Convert RGB to CMYK. This uses the current color RGB values and updates the 
   * CMYK values accordingly.
   * @private
   */
  // Quote from Wikipedia:
  // "Since RGB and CMYK spaces are both device-dependent spaces, there is no 
  // simple or general conversion formula that converts between them.  
  // Conversions are generally done through color management systems, using 
  // color profiles that describe the spaces being converted. Nevertheless, the 
  // conversions cannot be exact, since these spaces have very different 
  // gamuts."
  // Translation: this is just a simple RGB to CMYK conversion function.
  this.rgb2cmyk = function () {
    var color = _self.color,
        cyan, magenta, yellow, black,
        red   = color.red,
        green = color.green,
        blue  = color.blue;

    cyan    = 1 - red;
    magenta = 1 - green;
    yellow  = 1 - blue;

    black = MathMin(cyan, magenta, yellow, 1);

    if (black === 1) {
      cyan = magenta = yellow = 0;
    } else {
      var w = 1 - black;
      cyan    = (cyan    - black) / w;
      magenta = (magenta - black) / w;
      yellow  = (yellow  - black) / w;
    }

    color.cyan    = cyan;
    color.magenta = magenta;
    color.yellow  = yellow;
    color.black   = black;
  };

  /**
   * Convert CMYK to RGB (internally).
   * @private
   */
  this.cmyk2rgb = function () {
    var color = _self.color,
        w = 1 - color.black;

    color.red   = 1 - color.cyan    * w - color.black;
    color.green = 1 - color.magenta * w - color.black;
    color.blue  = 1 - color.yellow  * w - color.black;
  };

  /**
   * Convert RGB to HSV (internally).
   * @private
   */
  this.rgb2hsv = function () {
    var hue, sat, val, // HSV
        red   = _self.color.red,
        green = _self.color.green,
        blue  = _self.color.blue,
        min   = MathMin(red, green, blue),
        max   = MathMax(red, green, blue),
        delta = max - min,
        val   = max;

    // This is gray (red==green==blue)
    if (delta === 0) {
      hue = sat = 0;
    } else {
      sat = delta / max;

      if (max === red) {
        hue = (green -  blue) / delta;
      } else if (max === green) {
        hue = (blue  -   red) / delta + 2;
      } else if (max ===  blue) {
        hue = (red   - green) / delta + 4;
      }

      hue /= 6;
      if (hue < 0) {
        hue += 1;
      }
    }

    _self.color.hue = hue;
    _self.color.sat = sat;
    _self.color.val = val;
  };

  /**
   * Convert HSV to RGB.
   *
   * @private
   *
   * @param {Boolean} [no_update] Tells the function to not update the internal 
   * RGB color values.
   * @param {Array} [hsv] The array holding the HSV values you want to convert 
   * to RGB. This array must have three elements ordered as: <var>hue</var>, 
   * <var>saturation</var> and <var>value</var> - all between 0 and 1. If you do 
   * not provide the array, then the internal HSV color values are used.
   *
   * @returns {Array} The RGB values converted from HSV. The array has three 
   * elements ordered as: <var>red</var>, <var>green</var> and <var>blue</var> 
   * - all with values between 0 and 1.
   */
  this.hsv2rgb = function (no_update, hsv) {
    var color = _self.color,
        red, green, blue, hue, sat, val;

    // Use custom HSV values or the current color.
    if (hsv) {
      hue = hsv[0];
      sat = hsv[1];
      val = hsv[2];
    } else {
      hue = color.hue,
      sat = color.sat,
      val = color.val;
    }

    // achromatic (grey)
    if (sat === 0) {
      red = green = blue = val;
    } else {
      var h = hue * 6;
      var i = MathFloor(h);
      var t1 = val * ( 1 - sat ),
          t2 = val * ( 1 - sat * ( h - i ) ),
          t3 = val * ( 1 - sat * ( 1 - (h - i) ) );

      if (i === 0 || i === 6) { //   0° Red
        red = val;  green =  t3;  blue =  t1;
      } else if (i === 1) {    //  60° Yellow
        red =  t2;  green = val;  blue =  t1;
      } else if (i === 2) {    // 120° Green
        red =  t1;  green = val;  blue =  t3;
      } else if (i === 3) {    // 180° Cyan
        red =  t1;  green =  t2;  blue = val;
      } else if (i === 4) {    // 240° Blue
        red =  t3;  green =  t1;  blue = val;
      } else if (i === 5) {    // 300° Magenta
        red = val;  green =  t1;  blue =  t2;
      }
    }

    if (!no_update) {
      color.red   = red;
      color.green = green;
      color.blue  = blue;
    }

    return [red, green, blue];
  };

  /**
   * Convert RGB to hexadecimal representation (internally).
   * @private
   */
  this.rgb2hex = function () {
    var hex = '#', rgb = ['red', 'green', 'blue'], i, val,
        color = _self.color;

    for (i = 0; i < 3; i++) {
      val = MathRound(color[rgb[i]] * 255).toString(16);
      if (val.length === 1) {
        val = '0' + val;
      }
      hex += val;
    }

    color.hex = hex;
  };

  /**
   * Convert the hexadecimal representation of color to RGB values (internally).
   * @private
   */
  this.hex2rgb = function () {
    var rgb = ['red', 'green', 'blue'], i, val,
        color = _self.color,
        hex   = color.hex;

    hex = hex.substr(1);
    if (hex.length !== 6) {
      return;
    }

    for (i = 0; i < 3; i++) {
      val = hex.substr(i*2, 2);
      color[rgb[i]] = parseInt(val, 16)/255;
    }
  };

  /**
   * Convert RGB to CIE Lab (internally).
   * @private
   */
  this.rgb2lab = function () {
    var color = _self.color,
        lab   = _self.xyz2lab(_self.rgb2xyz([color.red, color.green, 
              color.blue]));

    color.cie_l = lab[0];
    color.cie_a = lab[1];
    color.cie_b = lab[2];
  };

  /**
   * Convert CIE Lab values to RGB values (internally).
   * @private
   */
  this.lab2rgb = function () {
    var color = _self.color,
        rgb   = _self.xyz2rgb(_self.lab2xyz(color.cie_l, color.cie_a, 
              color.cie_b));

    color.red   = rgb[0];
    color.green = rgb[1];
    color.blue  = rgb[2];
  };

  /**
   * Convert XYZ color values into CIE Lab values.
   *
   * @private
   *
   * @param {Array} xyz The array holding the XYZ color values in order: 
   * <var>X</var>, <var>Y</var> and <var>Z</var>.
   *
   * @returns {Array} An array holding the CIE Lab values in order: 
   * <var>L</var>, <var>a</var> and <var>b</var>.
   */
  this.xyz2lab = function (xyz) {
    var cfg = config.lab,

        // 216/24389 or (6/29)^3 (both = 0.008856...)
        e = 216/24389,

        // 903.296296...
        k = 24389/27;

    xyz[0] /= cfg.w_x;
    xyz[1] /= cfg.w_y;
    xyz[2] /= cfg.w_z;

    if (xyz[0] > e) {
      xyz[0] = MathPow(xyz[0], 1/3);
    } else {
      xyz[0] = (k*xyz[0] + 16)/116;
    }

    if (xyz[1] > e) {
      xyz[1] = MathPow(xyz[1], 1/3);
    } else {
      xyz[1] = (k*xyz[1] + 16)/116;
    }

    if (xyz[2] > e) {
      xyz[2] = MathPow(xyz[2], 1/3);
    } else {
      xyz[2] = (k*xyz[2] + 16)/116;
    }

    var cie_l = 116 *  xyz[1] - 16,
        cie_a = 500 * (xyz[0] -  xyz[1]),
        cie_b = 200 * (xyz[1] -  xyz[2]);

    return [cie_l, cie_a, cie_b];
  };

  /**
   * Convert CIE Lab values to XYZ color values.
   *
   * @private
   *
   * @param {Number} cie_l The color lightness value.
   * @param {Number} cie_a The a* color opponent.
   * @param {Number} cie_b The b* color opponent.
   *
   * @returns {Array} An array holding the XYZ color values in order: 
   * <var>X</var>, <var>Y</var> and <var>Z</var>.
   */
  this.lab2xyz = function (cie_l, cie_a, cie_b) {
    var y = (cie_l + 16) / 116,
        x = y + cie_a / 500,
        z = y - cie_b / 200,

        // 0.206896551...
        e = 6/29,

        // 7.787037...
        k = 1/3 * MathPow(29/6, 2),

        // 0.137931...
        t = 16/116,
        cfg = config.lab;

    if (x > e) {
      x = MathPow(x, 3);
    } else {
      x = (x - t) / k;
    }

    if (y > e) {
      y = MathPow(y, 3);
    } else {
      y = (y - t) / k;
    }

    if (z > e) {
      z = MathPow(z, 3);
    } else {
      z = (z - t) / k;
    }

    x *= cfg.w_x;
    y *= cfg.w_y;
    z *= cfg.w_z;

    return [x, y, z];
  };

  /**
   * Convert XYZ color values to RGB.
   *
   * @private
   *
   * @param {Array} xyz The array holding the XYZ color values in order: 
   * <var>X</var>, <var>Y</var> and <var>Z</var>
   *
   * @returns {Array} An array holding the RGB values in order: <var>red</var>, 
   * <var>green</var> and <var>blue</var>.
   */
  this.xyz2rgb = function (xyz) {
    var rgb = _self.calc_m1x3(xyz, config.lab.m_i);

    if (rgb[0] > 0.0031308) {
      rgb[0] = 1.055 * MathPow(rgb[0], 1 / 2.4) - 0.055;
    } else {
      rgb[0] *= 12.9232;
    }

    if (rgb[1] > 0.0031308) {
      rgb[1] = 1.055 * MathPow(rgb[1], 1 / 2.4) - 0.055;
    } else {
      rgb[1] *= 12.9232;
    }

    if (rgb[2] > 0.0031308) {
      rgb[2] = 1.055 * MathPow(rgb[2], 1 / 2.4) - 0.055;
    } else {
      rgb[2] *= 12.9232;
    }

    if (rgb[0] < 0) {
      rgb[0] = 0;
    } else if (rgb[0] > 1) {
      rgb[0] = 1;
    }

    if (rgb[1] < 0) {
      rgb[1] = 0;
    } else if (rgb[1] > 1) {
      rgb[1] = 1;
    }

    if (rgb[2] < 0) {
      rgb[2] = 0;
    } else if (rgb[2] > 1) {
      rgb[2] = 1;
    }

    return rgb;
  };

  /**
   * Convert RGB values to XYZ color values.
   *
   * @private
   *
   * @param {Array} rgb The array holding the RGB values in order: 
   * <var>red</var>, <var>green</var> and <var>blue</var>.
   *
   * @returns {Array} An array holding the XYZ color values in order: 
   * <var>X</var>, <var>Y</var> and <var>Z</var>.
   */
  this.rgb2xyz = function (rgb) {
    if (rgb[0] > 0.04045) {
      rgb[0] = MathPow(( rgb[0] + 0.055 ) / 1.055, 2.4);
    } else {
      rgb[0] /= 12.9232;
    }

    if (rgb[1] > 0.04045) {
      rgb[1] = MathPow(( rgb[1] + 0.055 ) / 1.055, 2.4);
    } else {
      rgb[1] /= 12.9232;
    }

    if (rgb[2] > 0.04045) {
      rgb[2] = MathPow(( rgb[2] + 0.055 ) / 1.055, 2.4);
    } else {
      rgb[2] /= 12.9232;
    }

    return _self.calc_m1x3(rgb, config.lab.m);
  };

  /**
   * Update the color space visualisation. This method updates the color chart 
   * and/or the color slider, and the associated controls, each as needed when 
   * a color key is updated.
   *
   * @private
   *
   * @param {String} updated_ckey The color key that was updated.
   * @param {Boolean} [force=false] Tells the function to force an update. The 
   * Canvas is not updated when the color mixer panel is not visible.
   *
   * @returns {Boolean} If the operation was successful, or false if not.
   */
  this.update_canvas = function (updated_ckey, force) {
    if (_self.panelSelector.tabId !== 'mixer' && !force) {
      _self.update_canvas_needed = true;
      return true;
    }

    _self.update_canvas_needed = false;

    var slider  = _self.elems.slider.style,
        chart   = _self.elems.chartDot.style,
        color   = _self.color,
        ckey    = _self.ckey_active,
        group   = _self.ckey_active_group,
        adjoint = _self.ckey_adjoint,
        width   = _self.chartWidth  / resScale,
        height  = _self.chartHeight / resScale,
        mx, my, sy;

    // Update the slider which shows the position of the active ckey.
    if (updated_ckey !== adjoint[0] && updated_ckey !== adjoint[1] && 
        _self.ev_canvas_mode !== 'chart') {
      if (group === 'lab') {
        sy = (color[ckey] - config.inputValues[ckey][0]) / _self.abs_max[ckey];
      } else {
        sy = color[ckey];
      }

      if (ckey !== 'hue' && group !== 'lab') {
        sy = 1 - sy;
      }

      slider.top = MathRound(sy * height) + 'px';
    }

    // Update the chart dot.
    if (updated_ckey !== ckey) {
      if (group === 'lab') {
        mx = (color[adjoint[0]] - config.inputValues[adjoint[0]][0]) 
          / _self.abs_max[adjoint[0]];
        my = (color[adjoint[1]] - config.inputValues[adjoint[1]][0]) 
          / _self.abs_max[adjoint[1]];
      } else {
        mx = color[adjoint[0]];
        my = 1 - color[adjoint[1]];
      }

      chart.top  = MathRound(my * height) + 'px';
      chart.left = MathRound(mx *  width) + 'px';
    }

    if (!_self.draw_chart(updated_ckey) || !_self.draw_slider(updated_ckey)) {
      return false;
    } else {
      return true;
    }
  };

  /**
   * The mouse events handler for the Canvas controls. This method determines 
   * the region the user is using, and it also updates the color values for the 
   * active color key. The Canvas and all the inputs in the color mixer are 
   * updated as needed.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.ev_canvas = function (ev) {
    ev.preventDefault();

    // Initialize color picking only on mousedown.
    if (ev.type === 'mousedown' && !_self.ev_canvas_mode) {
      _self.ev_canvas_mode = true;
      doc.addEventListener('mouseup', _self.ev_canvas, false);
    }

    if (!_self.ev_canvas_mode) {
      return false;
    }

    // The mouseup event stops the effect of any further mousemove events.
    if (ev.type === 'mouseup') {
      _self.ev_canvas_mode = false;
      doc.removeEventListener('mouseup', _self.ev_canvas, false);
    }

    var elems = _self.elems;

    // If the user is on top of the 'controls' element, determine the mouse coordinates and the 'mode' for this function: the user is either working with the slider, or he/she is working with the color chart itself.
    if (ev.target === elems.controls) {
      var mx, my,
          width  = _self.context2d.canvas.width,
          height = _self.context2d.canvas.height;

      // Get the mouse position, relative to the event target.
      if (ev.layerX || ev.layerX === 0) { // Firefox
        mx = ev.layerX * resScale;
        my = ev.layerY * resScale;
      } else if (ev.offsetX || ev.offsetX === 0) { // Opera
        mx = ev.offsetX * resScale;
        my = ev.offsetY * resScale;
      }

      if (mx >= 0 && mx <= _self.chartWidth) {
        mode = 'chart';
      } else if (mx >= _self.sliderX && mx <= width) {
        mode = 'slider';
      }
    } else {
      // The user might have clicked on the chart dot, or on the slider graphic 
      // itself.
      // If yes, then determine the mode based on this.
      if (ev.target === elems.chartDot) {
        mode = 'chart';
      } else if (ev.target === elems.slider) {
        mode = 'slider';
      }
    }

    // Update the ev_canvas_mode value to include the mode name, if it's simply 
    // the true boolean.
    // This ensures that the continuous mouse movements do not go from one mode 
    // to another when the user moves out from the slider to the chart (and 
    // vice-versa).
    if (mode && _self.ev_canvas_mode === true) {
      _self.ev_canvas_mode = mode;
    }

    // Do not continue if the mode wasn't determined (the mouse is not on the 
    // slider, nor on the chart).
    // Also don't continue if the mouse is not in the same place (different 
    // mode).
    if (!mode || _self.ev_canvas_mode !== mode || ev.target !== elems.controls) 
    {
      return false;
    }

    var color = _self.color,
        val_x = mx / _self.chartWidth,
        val_y = my / height;

    if (mode === 'slider') {
      if (_self.ckey_active === 'hue') {
        color[_self.ckey_active] = val_y;
      } else if (_self.ckey_active_group === 'lab') {
        color[_self.ckey_active] = _self.abs_max[_self.ckey_active] * val_y 
          + config.inputValues[_self.ckey_active][0];
      } else {
        color[_self.ckey_active] = 1 - val_y;
      }

      return _self.update_color(_self.ckey_active);

    } else if (mode === 'chart') {
      if (val_x > 1) {
        return false;
      }

      if (_self.ckey_active_group === 'lab') {
        val_x = _self.abs_max[_self.ckey_adjoint[0]] * val_x 
          + config.inputValues[_self.ckey_adjoint[0]][0];
        val_y = _self.abs_max[_self.ckey_adjoint[1]] * val_y 
          + config.inputValues[_self.ckey_adjoint[1]][0];
      } else {
        val_y = 1 - val_y;
      }

      color[_self.ckey_adjoint[0]] = val_x;
      color[_self.ckey_adjoint[1]] = val_y;

      return _self.update_color(_self.ckey_active_group);
    }

    return false;
  };

  /**
   * Draw the color space visualisation.
   *
   * @private
   *
   * @param {String} updated_ckey The color key that was updated. This is used 
   * to determine if the Canvas needs to be updated or not.
   */
  this.draw_chart = function (updated_ckey) {
    var context = _self.context2d,
        gradient, color, opacity, i;

    if (updated_ckey === _self.ckey_adjoint[0] || updated_ckey === 
        _self.ckey_adjoint[1] || (_self.ev_canvas_mode === 'chart' && 
          updated_ckey === _self.ckey_active_group)) {
      return true;
    }

    var w = _self.chartWidth,
        h = _self.chartHeight;

    context.clearRect(0, 0, w, h);

    if (_self.ckey_active === 'sat') {
      // In saturation mode the user has the slider which allows him/her to 
      // change the saturation (hSv) of the current color.
      // The chart shows the hue spectrum on the X axis, while the Y axis gives 
      // the Value (hsV).

      if (_self.color.sat > 0) {
        // Draw the hue spectrum gradient on the X axis.
        gradient = context.createLinearGradient(0, 0, w, 0);
        for (i = 0; i <= 6; i++) {
          color = 'rgb(' + hueSpectrum[i][0] + ', ' +
              hueSpectrum[i][1] + ', ' +
              hueSpectrum[i][2] + ')';
          gradient.addColorStop(i * 1/6, color);
        }
        context.fillStyle = gradient;
        context.fillRect(0, 0, w, h);

        // Draw the gradient which darkens the hue spectrum on the Y axis.
        gradient = context.createLinearGradient(0, 0, 0, h);
        gradient.addColorStop(0, 'rgba(0, 0, 0, 0)');
        gradient.addColorStop(1, 'rgba(0, 0, 0, 1)');
        context.fillStyle = gradient;
        context.fillRect(0, 0, w, h);
      }

      if (_self.color.sat < 1) {
        // Draw the white to black gradient. This is used for creating the 
        // saturation effect. Lowering the saturation value makes the gradient 
        // more visible, hence the hue colors desaturate.
        opacity = 1 - _self.color.sat;
        gradient = context.createLinearGradient(0, 0, 0, h);
        gradient.addColorStop(0, 'rgba(255, 255, 255, ' + opacity + ')');
        gradient.addColorStop(1, 'rgba(  0,   0,   0, ' + opacity + ')');
        context.fillStyle = gradient;
        context.fillRect(0, 0, w, h);
      }

    } else if (_self.ckey_active === 'val') {
      // In value mode the user has the slider which allows him/her to change the value (hsV) of the current color.
      // The chart shows the hue spectrum on the X axis, while the Y axis gives the saturation (hSv).

      if (_self.color.val > 0) {
        // Draw the hue spectrum gradient on the X axis.
        gradient = context.createLinearGradient(0, 0, w, 0);
        for (i = 0; i <= 6; i++) {
          color = 'rgb(' + hueSpectrum[i][0] + ', ' +
            hueSpectrum[i][1] + ', ' +
            hueSpectrum[i][2] + ')';
          gradient.addColorStop(i * 1/6, color);
        }
        context.fillStyle = gradient;
        context.fillRect(0, 0, w, h);

        // Draw the gradient which lightens the hue spectrum on the Y axis.
        gradient = context.createLinearGradient(0, 0, 0, h);
        gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 1)');
        context.fillStyle = gradient;
        context.fillRect(0, 0, w, h);
      }

      if (_self.color.val < 1) {
        // Draw a solid black color on top. This is used for darkening the hue colors gradient when the user reduces the Value (hsV).
        context.fillStyle = 'rgba(0, 0, 0, ' + (1 - _self.color.val) +')';
        context.fillRect(0, 0, w, h);
      }

    } else if (_self.ckey_active === 'hue') {
      // In hue mode the user has the slider which allows him/her to change the hue (Hsv) of the current color.
      // The chart shows the current color in the background. The X axis gives the saturation (hSv), and the Y axis gives the value (hsV).

      if (_self.color.sat === 1 && _self.color.val === 1) {
        color = [_self.color.red, _self.color.green, _self.color.blue];
      } else {
        // Determine the RGB values for the current color which has the same hue, but maximum saturation and value (hSV).
        color = _self.hsv2rgb(true, [_self.color.hue, 1, 1]);
      }
      for (i = 0; i < 3; i++) {
        color[i] = MathRound(color[i] * 255);
      }

      context.fillStyle = 'rgb(' + color[0] + ', ' + color[1] + ', ' + color[2] + ')';
      context.fillRect(0, 0, w, h);

      // Draw the white gradient for saturation (X axis, hSv).
      gradient = context.createLinearGradient(0, 0, w, 0);
      gradient.addColorStop(0, 'rgba(255, 255, 255, 1)');
      gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
      context.fillStyle = gradient;
      context.fillRect(0, 0, w, h);

      // Draw the black gradient for value (Y axis, hsV).
      gradient = context.createLinearGradient(0, 0, 0, h);
      gradient.addColorStop(0, 'rgba(0, 0, 0, 0)');
      gradient.addColorStop(1, 'rgba(0, 0, 0, 1)');
      context.fillStyle = gradient;
      context.fillRect(0, 0, w, h);

    } else if (_self.ckey_active_group === 'rgb') {
      // In any red/green/blue mode the background color becomes the one of the ckey_active. Say, for ckey_active=red the background color would be the current red value (green and blue are both set to 0).
      // On the X/Y axes the other two colors are shown. E.g. for red the X axis gives the green gradient, and the Y axis gives the blue gradient. The two gradients are drawn on top of the red background using a global composite operation (lighter) - to create the color addition effect.
      var color2, color3;

      color = {'red' : 0, 'green' : 0, 'blue' : 0};
      color[_self.ckey_active] = MathRound(_self.color[_self.ckey_active] 
          * 255);

      color2 = {'red' : 0, 'green' : 0, 'blue' : 0};
      color2[_self.ckey_adjoint[1]] = 255;

      color3 = {'red' : 0, 'green' : 0, 'blue' : 0};
      color3[_self.ckey_adjoint[0]] = 255;

      // The background.
      context.fillStyle = 'rgb(' + color.red + ',' + color.green + ',' + color.blue + ')';
      context.fillRect(0, 0, w, h);

      // This doesn't work in Opera 9.2 and older versions.
      var op = context.globalCompositeOperation;
      context.globalCompositeOperation = 'lighter';

      // The Y axis gradient.
      gradient = context.createLinearGradient(0, 0, 0, h);
      gradient.addColorStop(0, 'rgba(' + color2.red + ',' + color2.green + ',' + color2.blue + ', 1)');
      gradient.addColorStop(1, 'rgba(' + color2.red + ',' + color2.green + ',' + color2.blue + ', 0)');
      context.fillStyle = gradient;
      context.fillRect(0, 0, w, h);

      // The X axis gradient.
      gradient = context.createLinearGradient(0, 0, w, 0);
      gradient.addColorStop(0, 'rgba(' + color3.red + ',' + color3.green + ',' + color3.blue + ', 0)');
      gradient.addColorStop(1, 'rgba(' + color3.red + ',' + color3.green + ',' + color3.blue + ', 1)');
      context.fillStyle = gradient;
      context.fillRect(0, 0, w, h);

      context.globalCompositeOperation = op;

    } else if (_self.ckey_active_group === 'lab') {
      // The chart plots the CIE Lab colors. The non-active color keys give the X/Y axes. For example, if cie_l (lightness) is active, then the cie_a values give the X axis, and the Y axis is given by the values of cie_b.
      // The chart is drawn manually, pixel-by-pixel, due to the special way CIE Lab works. This is very slow in today's UAs.

      var imgd = false;

      if (context.createImageData) {
        imgd = context.createImageData(w, h);
      } else if (context.getImageData) {
        imgd = context.getImageData(0, 0, w, h);
      } else {
        imgd = {
          'width'  : w,
          'height' : h,
          'data'   : new Array(w*h*4)
        };
      }

      var pix = imgd.data,
          n = imgd.data.length - 1,
          i = -1, p = 0, inc_x, inc_y, xyz = [], rgb = [], cie_x, cie_y;

      cie_x = _self.ckey_adjoint[0];
      cie_y = _self.ckey_adjoint[1];

      color = {
        'cie_l' : _self.color.cie_l,
        'cie_a' : _self.color.cie_a,
        'cie_b' : _self.color.cie_b
      };

      inc_x = _self.abs_max[cie_x] / w;
      inc_y = _self.abs_max[cie_y] / h;

      color[cie_x] = config.inputValues[cie_x][0];
      color[cie_y] = config.inputValues[cie_y][0];

      while (i < n) {
        xyz = _self.lab2xyz(color.cie_l, color.cie_a, color.cie_b);
        rgb = _self.xyz2rgb(xyz);

        pix[++i] = MathRound(rgb[0]*255);
        pix[++i] = MathRound(rgb[1]*255);
        pix[++i] = MathRound(rgb[2]*255);
        pix[++i] = 255;

        p++;
        color[cie_x] += inc_x;

        if ((p % w) === 0) {
          color[cie_x] = config.inputValues[cie_x][0];
          color[cie_y] += inc_y;
        }
      }

      context.putImageData(imgd, 0, 0);
    }

    return true;
  };

  /**
   * Draw the color slider on the Canvas element.
   *
   * @private
   *
   * @param {String} updated_ckey The color key that was updated. This is used 
   * to determine if the Canvas needs to be updated or not.
   */
  this.draw_slider = function (updated_ckey) {
    if (_self.ckey_active === updated_ckey) {
      return true;
    }

    var context  = _self.context2d,
        slider_w = _self.sliderWidth,
        slider_h = _self.sliderHeight,
        slider_x = _self.sliderX,
        slider_y = 0,
        gradient, color, i;

    gradient = context.createLinearGradient(slider_x, slider_y, slider_x, slider_h);

    if (_self.ckey_active === 'hue') {
      // Draw the hue spectrum gradient.
      for (i = 0; i <= 6; i++) {
        color = 'rgb(' + hueSpectrum[i][0] + ', ' +
            hueSpectrum[i][1] + ', ' +
            hueSpectrum[i][2] + ')';
        gradient.addColorStop(i * 1/6, color);
      }
      context.fillStyle = gradient;
      context.fillRect(slider_x, slider_y, slider_w, slider_h);

      if (_self.color.sat < 1) {
        context.fillStyle = 'rgba(255, 255, 255, ' +
          (1 - _self.color.sat) + ')';
        context.fillRect(slider_x, slider_y, slider_w, slider_h);
      }
      if (_self.color.val < 1) {
        context.fillStyle = 'rgba(0, 0, 0, ' + (1 - _self.color.val) + ')';
        context.fillRect(slider_x, slider_y, slider_w, slider_h);
      }

    } else if (_self.ckey_active === 'sat') {
      // Draw the saturation gradient for the slider.
      // The start color is the current color with maximum saturation. The bottom gradient color is the same "color" without saturation.
      // The slider allows you to desaturate the current color.

      // Determine the RGB values for the current color which has the same hue and value (HsV), but maximum saturation (hSv).
      if (_self.color.sat === 1) {
        color = [_self.color.red, _self.color.green, _self.color.blue];
      } else {
        color = _self.hsv2rgb(true, [_self.color.hue, 1, _self.color.val]);
      }

      for (i = 0; i < 3; i++) {
        color[i] = MathRound(color[i] * 255);
      }

      var gray = MathRound(_self.color.val * 255);
      gradient.addColorStop(0, 'rgb(' + color[0] + ', ' + color[1] + ', ' + color[2] + ')');
      gradient.addColorStop(1, 'rgb(' + gray     + ', ' + gray     + ', ' + gray     + ')');
      context.fillStyle = gradient;
      context.fillRect(slider_x, slider_y, slider_w, slider_h);

    } else if (_self.ckey_active === 'val') {
      // Determine the RGB values for the current color which has the same hue and saturation, but maximum value (hsV).
      if (_self.color.val === 1) {
        color = [_self.color.red, _self.color.green, _self.color.blue];
      } else {
        color = _self.hsv2rgb(true, [_self.color.hue, _self.color.sat, 1]);
      }

      for (i = 0; i < 3; i++) {
        color[i] = MathRound(color[i] * 255);
      }

      gradient.addColorStop(0, 'rgb(' + color[0] + ', ' + color[1] + ', ' + color[2] + ')');
      gradient.addColorStop(1, 'rgb(0, 0, 0)');
      context.fillStyle = gradient;
      context.fillRect(slider_x, slider_y, slider_w, slider_h);

    } else if (_self.ckey_active_group === 'rgb') {
      var red   = MathRound(_self.color.red   * 255),
          green = MathRound(_self.color.green * 255),
          blue  = MathRound(_self.color.blue  * 255);

      color = {
        'red'   : red,
        'green' : green,
        'blue'  : blue
      };
      color[_self.ckey_active] = 255;

      var color2 = {
        'red'   : red,
        'green' : green,
        'blue'  : blue
      };
      color2[_self.ckey_active] = 0;

      gradient.addColorStop(0, 'rgb(' + color.red  + ',' + color.green  + ',' + color.blue  + ')');
      gradient.addColorStop(1, 'rgb(' + color2.red + ',' + color2.green + ',' + color2.blue + ')');
      context.fillStyle = gradient;
      context.fillRect(slider_x, slider_y, slider_w, slider_h);

    } else if (_self.ckey_active_group === 'lab') {
      // The slider shows a gradient with the current color key going from the minimum to the maximum value. The gradient is calculated pixel by pixel, due to the special way CIE Lab is defined.

      var imgd = false;

      if (context.createImageData) {
        imgd = context.createImageData(1, slider_h);
      } else if (context.getImageData) {
        imgd = context.getImageData(0, 0, 1, slider_h);
      } else {
        imgd = {
          'width'  : 1,
          'height' : slider_h,
          'data'   : new Array(slider_h*4)
        };
      }

      var pix = imgd.data,
          n = imgd.data.length - 1,
          ckey = _self.ckey_active,
          i = -1, inc, xyz, rgb;

      color = {
        'cie_l' : _self.color.cie_l,
        'cie_a' : _self.color.cie_a,
        'cie_b' : _self.color.cie_b
      };

      color[ckey] = config.inputValues[ckey][0];
      inc = _self.abs_max[ckey] / slider_h;

      while (i < n) {
        xyz = _self.lab2xyz(color.cie_l, color.cie_a, color.cie_b);
        rgb = _self.xyz2rgb(xyz);
        pix[++i] = MathRound(rgb[0]*255);
        pix[++i] = MathRound(rgb[1]*255);
        pix[++i] = MathRound(rgb[2]*255);
        pix[++i] = 255;

        color[ckey] += inc;
      }

      for (i = 0; i <= slider_w; i++) {
        context.putImageData(imgd, slider_x+i, slider_y);
      }
    }

    context.strokeStyle = '#6d6d6d';
    context.strokeRect(slider_x, slider_y, slider_w, slider_h);

    return true;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-06-16 21:56:40 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Allows users to draw in PaintWeb using the keyboard, without 
 * any pointing device.
 */

/**
 * @class The MouseKeys extension.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.extensions.mousekeys = function (app) {
  var _self     = this,
      canvas    = app.buffer.canvas,
      config    = app.config,
      container = app.gui.elems.canvasContainer,
      doc       = app.doc,
      gui       = app.gui,
      image     = app.image,
      MathCeil  = Math.ceil,
      mouse     = app.mouse,
      tool      = app.tool || {};

  /**
   * Holds the current mouse movement speed in pixels.
   *
   * @private
   * @type Number
   */
  var speed = 1;

  /**
   * Holds the current mouse movement acceleration, taken from the 
   * configuration.
   *
   * @private
   * @type Number
   * @see PaintWeb.config.mousekeys.accel The mouse keys acceleration setting.
   */
  var accel = 0.1;

  /**
   * Holds a reference to the DOM element representing the pointer on top of the 
   * canvas element.
   *
   * @private
   * @type Element
   */
  var pointer = null;
  var pointerStyle = null;

  /**
   * The <code>extensionRegister</code> event handler. This initializes the 
   * extension by adding the pointer DOM element and by setting up the keyboard 
   * shortcuts.
   *
   * @returns {Boolean} True if the extension initialized successfully, or false 
   * if not.
   */
  this.extensionRegister = function () {
    accel = config.mousekeys.accel;

    pointer = doc.createElement('div');
    if (!pointer) {
      return false;
    }
    pointerStyle = pointer.style;

    pointer.className = gui.classPrefix + 'mousekeysPointer';
    pointerStyle.display = 'none';
    container.appendChild(pointer);

    canvas.addEventListener('mousemove', pointerMousemove, false);

    var action, keys, i, n, result = {};

    for (action in config.mousekeys.actions) {
      keys = config.mousekeys.actions[action];

      for (i = 0, n = keys.length; i < n; i++) {
        result[keys[i]] = {'extension': _self._id, 'action': action};
      }
    };

    pwlib.extend(config.keys, result);

    return true;
  };

  /**
   * The <code>extensionUnregister</code> event handler. This will remove the 
   * pointer DOM element and the canvas event listener.
   */
  this.extensionUnregister = function () {
    container.removeChild(pointer);
    canvas.removeEventListener('mousemove', pointerMousemove, false);

    var key, kobj;
    for (key in config.keys) {
      kobj = config.keys[key];
      if (kobj.extension === _self._id) {
        delete config.keys[key];
      }
    }
  };

  /**
   * Track the virtual pointer coordinates, by updating the position of the 
   * <var>pointer</var> element. This allows the keyboard users to see where 
   * they moved the virtual pointer.
   *
   * @param {Event} ev The DOM Event object.
   */
  function pointerMousemove (ev) {
    if (!('kobj_' in ev) || !('extension' in ev.kobj_) ||
        ev.kobj_.extension !== _self._id) {
      if (pointerStyle.display === 'block') {
        pointerStyle.display = 'none';
      }
    }
  };

  /**
   * The <code>keydown</code> event handler.
   *
   * <p>This method requires a DOM Event object which has the 
   * <var>ev.kobj_</var> object reference from the keyboard shortcuts 
   * configuration. The <var>kobj_</var> object must have the <var>action</var> 
   * property. Support for the "ButtonToggle" and the "ButtonClick" actions is 
   * implemented.
   * 
   * <p>The "ButtonToggle" action essentially means that a mouse event will be 
   * generated, either <code>mousedown</code> or <code>mouseup</code>. By 
   * alternating these two events, this method allows the user to start and stop 
   * the drawing operation at any moment using the keyboard shortcut they have 
   * configured.
   *
   * <p>Under typical usage, the "ButtonClick" action translates the 
   * <code>keydown</code> event to <code>mousedown</code>. The 
   * <code>keyup</code> event handler will also fire the <code>mouseup</code> 
   * event. This allows the user to simulate holding down the mouse button, 
   * while he/she holds down a key.
   *
   * <p>A <code>click</code> event is always fired after the firing of 
   * a <code>mouseup</code> event.
   *
   * <p>Irrespective of the key the user pressed, this method does always reset 
   * the speed and acceleration of the pointer movement.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the keyboard shortcut was recognized, or false 
   * if not.
   *
   * @see PaintWeb.config.mousekeys.actions The keyboard shortcuts configuration 
   * object.
   */
  this.keydown = function (ev) {
    speed = 1;
    accel = config.mousekeys.accel;

    if (pointerStyle.display === 'none') {
      pointerStyle.display = 'block';
      pointerStyle.top  = (mouse.y * image.canvasScale) + 'px';
      pointerStyle.left = (mouse.x * image.canvasScale) + 'px';

      if (mouse.buttonDown) {
        pointer.className += ' ' + gui.classPrefix + 'mouseDown';
      } else {
        pointer.className = pointer.className.replace(' ' + gui.classPrefix 
            + 'mouseDown', '');
      }
    }

    tool = app.tool || {};

    switch (ev.kobj_.action) {
      case 'ButtonToggle':
        if (mouse.buttonDown) {
          mouse.buttonDown = false;
          if ('mouseup' in tool) {
            tool.mouseup(ev);
          }
          if ('click' in tool) {
            tool.click(ev);
          }

        } else {
          mouse.buttonDown = true;

          if ('mousedown' in tool) {
            tool.mousedown(ev);
          }
        }
        break;

      case 'ButtonClick':
        if (!mouse.buttonDown) {
          mouse.buttonDown = true;

          if ('mousedown' in tool) {
            tool.mousedown(ev);
          }
        }

        break;

      default:
        return false;
    }

    if (mouse.buttonDown) {
      pointer.className += ' ' + gui.classPrefix + 'mouseDown';
    } else {
      pointer.className = pointer.className.replace(' ' + gui.classPrefix 
          + 'mouseDown', '');
    }

    return true;
  };

  /**
   * The <code>keypress</code> event handler.
   *
   * <p>This method requires a DOM Event object with a <var>ev.kobj_</var> 
   * object reference to the keyboard shortcut configuration. The keyboard 
   * shortcut configuration object must have the <var>action</var> property.
   *
   * <p>This event handler implements support for the following <var>param</var>  
   * values: "SouthWest", "South", "SouthEast", "West", "East", "NorthWest", 
   * "North" and "NorthEast", All of these values indicate the movement 
   * direction. This method generates synthetic <var>movemove</var> events based 
   * on the direction desired, effectively emulating the use of a real pointing 
   * device.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the keyboard shortcut was recognized, or false 
   * if not.
   *
   * @see PaintWeb.config.mousekeys.actions The keyboard shortcuts configuration 
   * object.
   */
  this.keypress = function (ev) {
    if (ev.shiftKey) {
      speed += speed * accel * 3;
    } else {
      speed += speed * accel;
    }

    var step = MathCeil(speed);

    switch (ev.kobj_.action) {
      case 'SouthWest':
        mouse.x -= step;
        mouse.y += step;
        break;
      case 'South':
        mouse.y += step;
        break;
      case 'SouthEast':
        mouse.x += step;
        mouse.y += step;
        break;
      case 'West':
        mouse.x -= step;
        break;
      case 'East':
        mouse.x += step;
        break;
      case 'NorthWest':
        mouse.x -= step;
        mouse.y -= step;
        break;
      case 'North':
        mouse.y -= step;
        break;
      case 'NorthEast':
        mouse.x += step;
        mouse.y -= step;
        break;
      default:
        return false;
    }

    if (mouse.x < 0) {
      mouse.x = 0;
    } else if (mouse.x > image.width) {
      mouse.x = image.width;
    }

    if (mouse.y < 0) {
      mouse.y = 0;
    } else if (mouse.y > image.height) {
      mouse.y = image.height;
    }

    pointerStyle.top  = (mouse.y * image.canvasScale) + 'px';
    pointerStyle.left = (mouse.x * image.canvasScale) + 'px';

    if ('mousemove' in tool) {
      tool.mousemove(ev);
    }

    return true;
  };

  /**
   * The <code>keyup</code> event handler.
   *
   * <p>This method requires a DOM Event object which has the 
   * <var>ev.kobj_</var> object reference from the keyboard shortcuts 
   * configuration. The <var>kobj_</var> object must have the <var>action</var> 
   * property. Support for the "ButtonClick" action is implemented.
   * 
   * <p>Under typical usage, the "ButtonClick" action translates the 
   * <code>keydown</code> event to <code>mousedown</code>. This event handler 
   * fires the <code>mouseup</code> event. This allows the user to simulate 
   * holding down the mouse button, while he/she holds down a key.
   *
   * <p>A <code>click</code> event is always fired after the firing of the 
   * <code>mouseup</code> event.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the keyboard shortcut was recognized, or false 
   * if not.
   *
   * @see PaintWeb.config.mousekeys.actions The keyboard shortcuts configuration 
   * object.
   */
  this.keyup = function (ev) {
    if (ev.kobj_.action == 'ButtonClick' && mouse.buttonDown) {
      mouse.buttonDown = false;

      if ('mouseup' in tool) {
        tool.mouseup(ev);
      }
      if ('click' in tool) {
        tool.click(ev);
      }

      pointer.className = pointer.className.replace(' ' + gui.classPrefix 
          + 'mouseDown', '');
      return true;
    }

    return false;
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

/*
 * Copyright (C) 2008, 2009, 2010 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2010-06-26 21:47:30 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview The default PaintWeb interface code.
 */

/**
 * @class The default PaintWeb interface.
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.gui = function (app) {
  var _self     = this,
      config    = app.config,
      doc       = app.doc,
      lang      = app.lang,
      MathRound = Math.round,
      pwlib     = window.pwlib,
      appEvent  = pwlib.appEvent,
      win       = app.win;

  this.app = app;
  this.idPrefix = 'paintweb' + app.UID + '_',
  this.classPrefix = 'paintweb_';

  /**
   * Holds references to DOM elements.
   * @type Object
   */
  this.elems = {};

  /**
   * Holds references to input elements associated to the PaintWeb configuration 
   * properties.
   * @type Object
   */
  this.inputs = {};

  /**
   * Holds references to DOM elements associated to configuration values.
   * @type Object
   */
  this.inputValues = {};

  /**
   * Holds references to DOM elements associated to color configuration 
   * properties.
   *
   * @type Object
   * @see pwlib.guiColorInput
   */
  this.colorInputs = {};

  /**
   * Holds references to DOM elements associated to each tool registered in the 
   * current PaintWeb application instance.
   *
   * @private
   * @type Object
   */
  this.tools = {};

  /**
   * Holds references to DOM elements associated to PaintWeb commands.
   *
   * @private
   * @type Object
   */
  this.commands = {};

  /**
   * Holds references to floating panels GUI components.
   *
   * @type Object
   * @see pwlib.guiFloatingPanel
   */
  this.floatingPanels = {zIndex_: 0};

  /**
   * Holds references to tab panel GUI components.
   *
   * @type Object
   * @see pwlib.guiTabPanel
   */
  this.tabPanels = {};

  /**
   * Holds an instance of the guiResizer object attached to the Canvas.
   *
   * @private
   * @type pwlib.guiResizer
   */
  this.canvasResizer = null;

  /**
   * Holds an instance of the guiResizer object attached to the viewport 
   * element.
   *
   * @private
   * @type pwlib.guiResizer
   */
  this.viewportResizer = null;

  /**
   * Holds tab configuration information for most drawing tools.
   *
   * @private
   * @type Object
   */
  this.toolTabConfig = {
    bcurve: {
      lineTab: true,
      shapeType: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.borderWidth,
      lineCap: true
    },
    ellipse: {
      lineTab: true,
      shapeType: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.borderWidth
    },
    rectangle: {
      lineTab: true,
      shapeType: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.borderWidth,
      lineJoin: true
    },
    polygon: {
      lineTab: true,
      shapeType: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.borderWidth,
      lineJoin: true,
      lineCap: true,
      miterLimit: true
    },
    eraser: {
      lineTab: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.eraserSize,
      lineJoin: true,
      lineCap: true,
      miterLimit: true
    },
    pencil: {
      lineTab: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.pencilSize,
      lineJoin: true,
      lineCap: true,
      miterLimit: true
    },
    line: {
      lineTab: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.line.lineWidth,
      lineJoin: true,
      lineCap: true,
      miterLimit: true
    },
    text: {
      lineTab: true,
      lineTabLabel: lang.tabs.main.textBorder,
      shapeType: true,
      lineWidth: true,
      lineWidthLabel: lang.inputs.borderWidth
    }
  };

  /**
   * Initialize the PaintWeb interface.
   *
   * @param {Document|String} markup The interface markup loaded and parsed as 
   * DOM Document object. Optionally, the value can be a string holding the 
   * interface markup (this is used when PaintWeb is packaged).
   *
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.init = function (markup) {
    // Make sure the user nicely waits for PaintWeb to load, without seeing 
    // much.
    var placeholder = config.guiPlaceholder,
        placeholderStyle = placeholder.style;


    placeholderStyle.display = 'none';
    placeholderStyle.height = '1px';
    placeholderStyle.overflow = 'hidden';
    placeholderStyle.position = 'absolute';
    placeholderStyle.visibility = 'hidden';

    placeholder.className += ' ' + this.classPrefix + 'placeholder';
    if (!placeholder.tabIndex || placeholder.tabIndex == -1) {
      placeholder.tabIndex = 1;
    }

    if (!this.initImportDoc(markup)) {
      app.initError(lang.guiMarkupImportFailed);
      return false;
    }
    markup = null;

    if (!this.initParseMarkup()) {
      app.initError(lang.guiMarkupParseFailed);
      return false;
    }

    if (!this.initCanvas() ||
        !this.initImageZoom() ||
        !this.initSelectionTool() ||
        !this.initTextTool() ||
        !this.initKeyboardShortcuts()) {
      return false;
    }

    // Setup the main tabbed panel.
    var panel = this.tabPanels.main;
    if (!panel) {
      app.initError(lang.noMainTabPanel);
      return false;
    }

    // Hide the "Shadow" tab if the drawing of shadows is not supported.
    if (!app.shadowSupported && 'shadow' in panel.tabs) {
      panel.tabHide('shadow');
    }

    if (!('viewport' in this.elems)) {
      app.initError(lang.missingViewport);
      return false;
    }

    // Setup the GUI dimensions .
    this.elems.viewport.style.height = config.viewportHeight;
    placeholderStyle.width = config.viewportWidth;

    // Setup the Canvas resizer.
    var resizeHandle = this.elems.canvasResizer;
    if (!resizeHandle) {
      app.initError(lang.missingCanvasResizer);
      return false;
    }
    resizeHandle.title = lang.guiCanvasResizer;
    resizeHandle.replaceChild(doc.createTextNode(resizeHandle.title), 
        resizeHandle.firstChild);
    resizeHandle.addEventListener('mouseover', this.item_mouseover, false);
    resizeHandle.addEventListener('mouseout',  this.item_mouseout,  false);

    this.canvasResizer = new pwlib.guiResizer(this, resizeHandle, 
        this.elems.canvasContainer);

    this.canvasResizer.events.add('guiResizeStart', this.canvasResizeStart);
    this.canvasResizer.events.add('guiResizeEnd',   this.canvasResizeEnd);

    // Setup the viewport resizer.
    var resizeHandle = this.elems.viewportResizer;
    if (!resizeHandle) {
      app.initError(lang.missingViewportResizer);
      return false;
    }
    resizeHandle.title = lang.guiViewportResizer;
    resizeHandle.replaceChild(doc.createTextNode(resizeHandle.title), 
        resizeHandle.firstChild);
    resizeHandle.addEventListener('mouseover', this.item_mouseover, false);
    resizeHandle.addEventListener('mouseout',  this.item_mouseout,  false);

    this.viewportResizer = new pwlib.guiResizer(this, resizeHandle, 
        this.elems.viewport);

    this.viewportResizer.dispatchMouseMove = true;
    this.viewportResizer.events.add('guiResizeMouseMove', 
        this.viewportResizeMouseMove);
    this.viewportResizer.events.add('guiResizeEnd', this.viewportResizeEnd);

    if ('statusMessage' in this.elems) {
      this.elems.statusMessage._prevText = false;
    }

    // Update the version string in Help.
    if ('version' in this.elems) {
      this.elems.version.appendChild(doc.createTextNode(app.toString()));
    }

    // Update the image dimensions in the GUI.
    var imageSize = this.elems.imageSize;
    if (imageSize) {
      imageSize.replaceChild(doc.createTextNode(app.image.width + 'x' 
            + app.image.height), imageSize.firstChild);
    }

    // Add application-wide event listeners.
    app.events.add('canvasSizeChange',  this.canvasSizeChange);
    app.events.add('commandRegister',   this.commandRegister);
    app.events.add('commandUnregister', this.commandUnregister);
    app.events.add('configChange',      this.configChangeHandler);
    app.events.add('imageSizeChange',   this.imageSizeChange);
    app.events.add('imageZoom',         this.imageZoom);
    app.events.add('appInit',           this.appInit);
    app.events.add('shadowAllow',       this.shadowAllow);
    app.events.add('toolActivate',      this.toolActivate);
    app.events.add('toolRegister',      this.toolRegister);
    app.events.add('toolUnregister',    this.toolUnregister);

    // Make sure the historyUndo and historyRedo command elements are 
    // synchronized with the application history state.
    if ('historyUndo' in this.commands && 'historyRedo' in this.commands) {
      app.events.add('historyUpdate', this.historyUpdate);
    }

    app.commandRegister('about', this.commandAbout);

    return true;
  };

  /**
   * Initialize the Canvas elements.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initCanvas = function () {
    var canvasContainer = this.elems.canvasContainer,
        layerCanvas     = app.layer.canvas,
        layerContext    = app.layer.context,
        layerStyle      = layerCanvas.style,
        bufferCanvas    = app.buffer.canvas;

    if (!canvasContainer) {
      app.initError(lang.missingCanvasContainer);
      return false;
    }

    var containerStyle  = canvasContainer.style;

    canvasContainer.className = this.classPrefix + 'canvasContainer';
    layerCanvas.className     = this.classPrefix + 'layerCanvas';
    bufferCanvas.className    = this.classPrefix + 'bufferCanvas';

    containerStyle.width  = layerStyle.width;
    containerStyle.height = layerStyle.height;
    if (!config.checkersBackground || pwlib.browser.olpcxo) {
      containerStyle.backgroundImage = 'none';
    }

    canvasContainer.appendChild(layerCanvas);
    canvasContainer.appendChild(bufferCanvas);

    // Make sure the selection transparency input checkbox is disabled if the 
    // putImageData and getImageData methods are unsupported.
    if ('selection_transparent' in this.inputs && (!layerContext.putImageData || 
          !layerContext.getImageData)) {
      this.inputs.selection_transparent.disabled = true;
      this.inputs.selection_transparent.checked = true;
    }

    return true;
  };

  /**
   * Import the DOM nodes from the interface DOM document. All the nodes are 
   * inserted into the {@link PaintWeb.config.guiPlaceholder} element.
   *
   * <p>Elements which have the ID attribute will have the attribute renamed to 
   * <code>data-pwId</code>.
   *
   * <p>Input elements which have the ID attribute will have their attribute 
   * updated to be unique for the current PaintWeb instance.
   *
   * @private
   *
   * @param {Document|String} markup The source DOM document to import the nodes 
   * from. Optionally, this parameter can be a string which holds the interface 
   * markup.
   *
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initImportDoc = function (markup) {
    // I could use some XPath here, but for the sake of compatibility I don't.
    var destElem = config.guiPlaceholder,
        elType = app.ELEMENT_NODE,
        elem, root, nodes, n, tag, isInput;

    if (typeof markup === 'string') {
      elem = doc.createElement('div');
      elem.innerHTML = markup;
      root = elem.firstChild;
    } else {
      root = markup.documentElement;
    }
    markup = null;

    nodes = root.getElementsByTagName('*');
    n = nodes.length;

    // Change all the id attributes to be data-pwId attributes.
    // Input elements have their ID updated to be unique for the current 
    // PaintWeb instance.
    for (var i = 0; i < n; i++) {
      elem = nodes[i];
      if (elem.nodeType !== elType || !elem.tagName) {
        continue;
      }
      tag = elem.tagName.toLowerCase();
      isInput = tag === 'input' || tag === 'select' || tag === 'textarea';

      if (elem.id) {
        elem.setAttribute('data-pwId', elem.id);

        if (isInput) {
          elem.id = this.idPrefix + elem.id;
        } else {
          elem.removeAttribute('id');
        }
      }

      // label elements have their "for" attribute updated as well.
      if (tag === 'label' && elem.htmlFor) {
        elem.htmlFor = this.idPrefix + elem.htmlFor;
      }
    }

    // Import all the nodes.
    n = root.childNodes.length;

    for (var i = 0; i < n; i++) {
      destElem.appendChild(doc.importNode(root.childNodes[i], true));
    }

    return true;
  };

  /**
   * Parse the interface markup. The layout file can have custom 
   * PaintWeb-specific attributes.
   *
   * <p>Elements with the <code>data-pwId</code> attribute are added to the 
   * {@link pwlib.gui#elems} object.
   *
   * <p>Elements having the <code>data-pwCommand</code> attribute are added to 
   * the {@link pwlib.gui#commands} object.
   *
   * <p>Elements having the <code>data-pwTool</code> attribute are added to the 
   * {@link pwlib.gui#tools} object.
   *
   * <p>Elements having the <code>data-pwTabPanel</code> attribute are added to 
   * the {@link pwlib.gui#tabPanels} object. These become interactive GUI 
   * components (see {@link pwlib.guiTabPanel}).
   *
   * <p>Elements having the <code>data-pwFloatingPanel</code> attribute are 
   * added to the {@link pwlib.gui#floatingPanels} object. These become 
   * interactive GUI components (see {@link pwlib.guiFloatingPanel}).
   *
   * <p>Elements having the <code>data-pwConfig</code> attribute are added to 
   * the {@link pwlib.gui#inputs} object. These become interactive GUI 
   * components which allow users to change configuration options.
   *
   * <p>Elements having the <code>data-pwConfigValue</code> attribute are added 
   * to the {@link pwlib.gui#inputValues} object. These can only be child nodes 
   * of elements which have the <code>data-pwConfig</code> attribute. Each such 
   * element is considered an icon. Anchor elements are appended to ensure 
   * keyboard accessibility.
   *
   * <p>Elements having the <code>data-pwConfigToggle</code> attribute are added 
   * to the {@link pwlib.gui#inputs} object. These become interactive GUI 
   * components which toggle the boolean value of the configuration property 
   * they are associated to.
   *
   * <p>Elements having the <code>data-pwColorInput</code> attribute are added 
   * to the {@link pwlib.gui#colorInputs} object. These become color picker 
   * inputs which are associated to the configuration property given as the 
   * attribute value. (see {@link pwlib.guiColorInput})
   *
   * @returns {Boolean} True if the parsing was successful, or false if not.
   */
  this.initParseMarkup = function () {
    var nodes = config.guiPlaceholder.getElementsByTagName('*'),
        elType = app.ELEMENT_NODE,
        elem, tag, isInput, tool, tabPanel, floatingPanel, cmd, id, cfgAttr, 
        colorInput;

    // Store references to important elements and parse PaintWeb-specific 
    // attributes.
    for (var i = 0; i < nodes.length; i++) {
      elem = nodes[i];
      if (elem.nodeType !== elType) {
        continue;
      }
      tag = elem.tagName.toLowerCase();
      isInput = tag === 'input' || tag === 'select' || tag === 'textarea';

      // Store references to commands.
      cmd = elem.getAttribute('data-pwCommand');
      if (cmd && !(cmd in this.commands)) {
        elem.className += ' ' + this.classPrefix + 'command';
        this.commands[cmd] = elem;
      }

      // Store references to tools.
      tool = elem.getAttribute('data-pwTool');
      if (tool && !(tool in this.tools)) {
        elem.className += ' ' + this.classPrefix + 'tool';
        this.tools[tool] = elem;
      }

      // Create tab panels.
      tabPanel = elem.getAttribute('data-pwTabPanel');
      if (tabPanel) {
        this.tabPanels[tabPanel] = new pwlib.guiTabPanel(this, elem);
      }

      // Create floating panels.
      floatingPanel = elem.getAttribute('data-pwFloatingPanel');
      if (floatingPanel) {
        this.floatingPanels[floatingPanel] = new pwlib.guiFloatingPanel(this, 
            elem);
      }

      cfgAttr = elem.getAttribute('data-pwConfig');
      if (cfgAttr) {
        if (isInput) {
          this.initConfigInput(elem, cfgAttr);
        } else {
          this.initConfigIcons(elem, cfgAttr);
        }
      }

      cfgAttr = elem.getAttribute('data-pwConfigToggle');
      if (cfgAttr) {
        this.initConfigToggle(elem, cfgAttr);
      }

      // elem.hasAttribute() fails in webkit (tested with chrome and safari 4)
      if (elem.getAttribute('data-pwColorInput')) {
        colorInput = new pwlib.guiColorInput(this, elem);
        this.colorInputs[colorInput.id] = colorInput;
      }

      id = elem.getAttribute('data-pwId');
      if (id) {
        elem.className += ' ' + this.classPrefix + id;

        // Store a reference to the element.
        if (isInput && !cfgAttr) {
          this.inputs[id] = elem;
        } else if (!isInput) {
          this.elems[id] = elem;
        }
      }
    }

    return true;
  };

  /**
   * Initialize an input element associated to a configuration property.
   *
   * @private
   *
   * @param {Element} elem The DOM element which is associated to the 
   * configuration property.
   *
   * @param {String} cfgAttr The configuration attribute. This tells the 
   * configuration group and property to which the DOM element is attached to.
   */
  this.initConfigInput = function (input, cfgAttr) {
    var cfgNoDots   = cfgAttr.replace('.', '_'),
        cfgArray    = cfgAttr.split('.'),
        cfgProp     = cfgArray.pop(),
        cfgGroup    = cfgArray.join('.'),
        cfgGroupRef = config,
        langGroup   = lang.inputs,
        labelElem   = input.parentNode;

    for (var i = 0, n = cfgArray.length; i < n; i++) {
      cfgGroupRef = cfgGroupRef[cfgArray[i]];
      langGroup = langGroup[cfgArray[i]];
    }

    input._pwConfigProperty = cfgProp;
    input._pwConfigGroup = cfgGroup;
    input._pwConfigGroupRef = cfgGroupRef;
    input.title = langGroup[cfgProp + 'Title'] || langGroup[cfgProp];
    input.className += ' ' + this.classPrefix + 'cfg_' + cfgNoDots;

    this.inputs[cfgNoDots] = input;

    if (labelElem.tagName.toLowerCase() !== 'label') {
      labelElem = labelElem.getElementsByTagName('label')[0];
    }

    if (input.type === 'checkbox' || labelElem.htmlFor) {
      labelElem.replaceChild(doc.createTextNode(langGroup[cfgProp]), 
          labelElem.lastChild);
    } else {
      labelElem.replaceChild(doc.createTextNode(langGroup[cfgProp]), 
          labelElem.firstChild);
    }

    if (input.type === 'checkbox') {
      input.checked = cfgGroupRef[cfgProp];
    } else {
      input.value = cfgGroupRef[cfgProp];
    }

    input.addEventListener('input',  this.configInputChange, false);
    input.addEventListener('change', this.configInputChange, false);
  };

  /**
   * Initialize an HTML element associated to a configuration property, and all 
   * of its own sub-elements associated to configuration values. Each element 
   * that has the <var>data-pwConfigValue</var> attribute is considered an icon.
   *
   * @private
   *
   * @param {Element} elem The DOM element which is associated to the 
   * configuration property.
   *
   * @param {String} cfgAttr The configuration attribute. This tells the 
   * configuration group and property to which the DOM element is attached to.
   */
  this.initConfigIcons = function (input, cfgAttr) {
    var cfgNoDots   = cfgAttr.replace('.', '_'),
        cfgArray    = cfgAttr.split('.'),
        cfgProp     = cfgArray.pop(),
        cfgGroup    = cfgArray.join('.'),
        cfgGroupRef = config,
        langGroup   = lang.inputs;

    for (var i = 0, n = cfgArray.length; i < n; i++) {
      cfgGroupRef = cfgGroupRef[cfgArray[i]];
      langGroup = langGroup[cfgArray[i]];
    }

    input._pwConfigProperty = cfgProp;
    input._pwConfigGroup = cfgGroup;
    input._pwConfigGroupRef = cfgGroupRef;
    input.title = langGroup[cfgProp + 'Title'] || langGroup[cfgProp];
    input.className += ' ' + this.classPrefix + 'cfg_' + cfgNoDots;

    this.inputs[cfgNoDots] = input;

    var labelElem = input.getElementsByTagName('p')[0];
    labelElem.replaceChild(doc.createTextNode(langGroup[cfgProp]), 
        labelElem.firstChild);

    var elem, anchor, val,
        className = ' ' + this.classPrefix + 'configActive';
        nodes = input.getElementsByTagName('*'),
        elType = app.ELEMENT_NODE;

    for (var i = 0; i < nodes.length; i++) {
      elem = nodes[i];
      if (elem.nodeType !== elType) {
        continue;
      }

      val = elem.getAttribute('data-pwConfigValue');
      if (!val) {
        continue;
      }

      anchor = doc.createElement('a');
      anchor.href = '#';
      anchor.title = langGroup[cfgProp + '_' + val];
      anchor.appendChild(doc.createTextNode(anchor.title));

      elem.className += ' ' + this.classPrefix + cfgProp + '_' + val 
        + ' ' + this.classPrefix + 'icon';
      elem._pwConfigParent = input;

      if (cfgGroupRef[cfgProp] == val) {
        elem.className += className;
      }

      anchor.addEventListener('click',     this.configValueClick, false);
      anchor.addEventListener('mouseover', this.item_mouseover,   false);
      anchor.addEventListener('mouseout',  this.item_mouseout,    false);

      elem.replaceChild(anchor, elem.firstChild);

      this.inputValues[cfgGroup + '_' + cfgProp + '_' + val] = elem;
    }
  };

  /**
   * Initialize an HTML element associated to a boolean configuration property.
   *
   * @private
   *
   * @param {Element} elem The DOM element which is associated to the 
   * configuration property.
   *
   * @param {String} cfgAttr The configuration attribute. This tells the 
   * configuration group and property to which the DOM element is attached to.
   */
  this.initConfigToggle = function (input, cfgAttr) {
    var cfgNoDots   = cfgAttr.replace('.', '_'),
        cfgArray    = cfgAttr.split('.'),
        cfgProp     = cfgArray.pop(),
        cfgGroup    = cfgArray.join('.'),
        cfgGroupRef = config,
        langGroup   = lang.inputs;

    for (var i = 0, n = cfgArray.length; i < n; i++) {
      cfgGroupRef = cfgGroupRef[cfgArray[i]];
      langGroup = langGroup[cfgArray[i]];
    }

    input._pwConfigProperty = cfgProp;
    input._pwConfigGroup = cfgGroup;
    input._pwConfigGroupRef = cfgGroupRef;
    input.className += ' ' + this.classPrefix + 'cfg_' + cfgNoDots 
      + ' ' + this.classPrefix + 'icon';

    if (cfgGroupRef[cfgProp]) {
      input.className += ' ' + this.classPrefix + 'configActive';
    }

    var anchor = doc.createElement('a');
    anchor.href = '#';
    anchor.title = langGroup[cfgProp + 'Title'] || langGroup[cfgProp];
    anchor.appendChild(doc.createTextNode(langGroup[cfgProp]));

    anchor.addEventListener('click',     this.configToggleClick, false);
    anchor.addEventListener('mouseover', this.item_mouseover,    false);
    anchor.addEventListener('mouseout',  this.item_mouseout,     false);

    input.replaceChild(anchor, input.firstChild);

    this.inputs[cfgNoDots] = input;
  };

  /**
   * Initialize the image zoom input.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initImageZoom = function () {
    var input = this.inputs.imageZoom;
    if (!input) {
      return true; // allow layouts without the zoom input
    }

    input.value = 100;
    input._old_value = 100;

    // Override the attributes, based on the settings.
    input.setAttribute('step', config.imageZoomStep * 100);
    input.setAttribute('max',  config.imageZoomMax  * 100);
    input.setAttribute('min',  config.imageZoomMin  * 100);

    var changeFn = function () {
      app.imageZoomTo(parseInt(this.value) / 100);
    };

    input.addEventListener('change', changeFn, false);
    input.addEventListener('input',  changeFn, false);

    // Update some language strings

    var label = input.parentNode;
    if (label.tagName.toLowerCase() === 'label') {
      label.replaceChild(doc.createTextNode(lang.imageZoomLabel), 
          label.firstChild);
    }

    var elem = this.elems.statusZoom;
    if (!elem) {
      return true;
    }

    elem.title = lang.imageZoomTitle;

    return true;
  };

  /**
   * Initialize GUI elements associated to selection tool options and commands.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initSelectionTool = function () {
    var classDisabled = ' ' + this.classPrefix + 'disabled',
        cut   = this.commands.selectionCut,
        copy  = this.commands.selectionCopy,
        paste = this.commands.clipboardPaste;

    if (paste) {
      app.events.add('clipboardUpdate', this.clipboardUpdate);
      paste.className += classDisabled;

    }

    if (cut && copy) {
      app.events.add('selectionChange', this.selectionChange);
      cut.className  += classDisabled;
      copy.className += classDisabled;
    }

    var selTab_cmds = ['selectionCut', 'selectionCopy', 'clipboardPaste'],
        anchor, elem, cmd;

    for (var i = 0, n = selTab_cmds.length; i < n; i++) {
      cmd = selTab_cmds[i];
      elem = this.elems['selTab_' + cmd];
      if (!elem) {
        continue;
      }

      anchor = doc.createElement('a');
      anchor.title = lang.commands[cmd];
      anchor.href = '#';
      anchor.appendChild(doc.createTextNode(anchor.title));
      anchor.addEventListener('click', this.commandClick, false);

      elem.className += classDisabled + ' ' + this.classPrefix + 'command' 
        + ' ' + this.classPrefix + 'cmd_' + cmd;
      elem.setAttribute('data-pwCommand', cmd);
      elem.replaceChild(anchor, elem.firstChild);
    }

    var selCrop   = this.commands.selectionCrop,
        selFill   = this.commands.selectionFill,
        selDelete = this.commands.selectionDelete;

    selCrop.className   += classDisabled;
    selFill.className   += classDisabled;
    selDelete.className += classDisabled;

    return true;
  };

  /**
   * Initialize GUI elements associated to text tool options.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initTextTool = function () {
    if ('textString' in this.inputs) {
      this.inputs.textString.value = lang.inputs.text.textString_value;
    }

    if (!('text_fontFamily' in this.inputs) || !('text' in config) || 
        !('fontFamilies' in config.text)) {
      return true;
    }

    var option, input = this.inputs.text_fontFamily;
    for (var i = 0, n = config.text.fontFamilies.length; i < n; i++) {
      option = doc.createElement('option');
      option.value = config.text.fontFamilies[i];
      option.appendChild(doc.createTextNode(option.value));
      input.appendChild(option);

      if (option.value === config.text.fontFamily) {
        input.selectedIndex = i;
        input.value = option.value;
      }
    }

    option = doc.createElement('option');
    option.value = '+';
    option.appendChild(doc.createTextNode(lang.inputs.text.fontFamily_add));
    input.appendChild(option);

    return true;
  };

  /**
   * Initialize the keyboard shortcuts. Basically, this updates various strings 
   * to ensure the user interface is informational.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initKeyboardShortcuts = function () {
    var kid = null, kobj = null;

    for (kid in config.keys) {
      kobj = config.keys[kid];

      if ('toolActivate' in kobj && kobj.toolActivate in lang.tools) {
        lang.tools[kobj.toolActivate] += ' [ ' + kid + ' ]';
      }

      if ('command' in kobj && kobj.command in lang.commands) {
        lang.commands[kobj.command] += ' [ ' + kid + ' ]';
      }
    }

    return true;
  };

  /**
   * The <code>appInit</code> event handler. This method is invoked once 
   * PaintWeb completes all the loading.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.guiShow} application 
   * event.
   *
   * @private
   * @param {pwlib.appEvent.appInit} ev The application event object.
   */
  this.appInit = function (ev) {
    // Initialization was not successful ...
    if (ev.state !== PaintWeb.INIT_DONE) {
      return;
    }

    // Make sure the Hand tool is enabled/disabled as needed.
    if ('hand' in _self.tools) {
      app.events.add('canvasSizeChange',   _self.toolHandStateChange);
      app.events.add('viewportSizeChange', _self.toolHandStateChange);
      _self.toolHandStateChange(ev);
    }

    // Make PaintWeb visible.
    var placeholder = config.guiPlaceholder,
        placeholderStyle = placeholder.style;

    // We do not reset the display property. We leave this for the stylesheet.
    placeholderStyle.height = '';
    placeholderStyle.overflow = '';
    placeholderStyle.position = '';
    placeholderStyle.visibility = '';

    var cs = win.getComputedStyle(placeholder, null);

    // Do not allow the static positioning for the PaintWeb placeholder.  
    // Usually, the GUI requires absolute/relative positioning.
    if (cs.position === 'static') {
      placeholderStyle.position = 'relative';
    }

    try {
      placeholder.focus();
    } catch (err) { }

    app.events.dispatch(new appEvent.guiShow());
  };

  /**
   * The <code>guiResizeStart</code> event handler for the Canvas resize 
   * operation.
   * @private
   */
  this.canvasResizeStart = function () {
    this.resizeHandle.style.visibility = 'hidden';

    // ugly...
    this.timeout_ = setTimeout(function () {
      _self.statusShow('guiCanvasResizerActive', true);
      clearTimeout(_self.canvasResizer.timeout_);
      delete _self.canvasResizer.timeout_;
    }, 400);
  };

  /**
   * The <code>guiResizeEnd</code> event handler for the Canvas resize 
   * operation.
   *
   * @private
   * @param {pwlib.appEvent.guiResizeEnd} ev The application event object.
   */
  this.canvasResizeEnd = function (ev) {
    this.resizeHandle.style.visibility = '';

    app.imageCrop(0, 0, MathRound(ev.width / app.image.canvasScale),
        MathRound(ev.height / app.image.canvasScale));

    if (this.timeout_) {
      clearTimeout(this.timeout_);
      delete this.timeout_;
    } else {
      _self.statusShow(-1);
    }
  };

  /**
   * The <code>guiResizeMouseMove</code> event handler for the viewport resize 
   * operation.
   *
   * @private
   * @param {pwlib.appEvent.guiResizeMouseMove} ev The application event object.
   */
  this.viewportResizeMouseMove = function (ev) {
    config.guiPlaceholder.style.width = ev.width + 'px';
  };

  /**
   * The <code>guiResizeEnd</code> event handler for the viewport resize 
   * operation.
   *
   * @private
   * @param {pwlib.appEvent.guiResizeEnd} ev The application event object.
   */
  this.viewportResizeEnd = function (ev) {
    _self.elems.viewport.style.width = '';
    _self.resizeTo(ev.width + 'px', ev.height + 'px');
    try {
      config.guiPlaceholder.focus();
    } catch (err) { }
  };

  /**
   * The <code>mouseover</code> event handler for all tools, commands and icons.  
   * This simply shows the title / text content of the element in the GUI status 
   * bar.
   *
   * @see pwlib.gui#statusShow The method used for displaying the message in the 
   * GUI status bar.
   */
  this.item_mouseover = function () {
    if (this.title || this.textConent) {
      _self.statusShow(this.title || this.textContent, true);
    }
  };

  /**
   * The <code>mouseout</code> event handler for all tools, commands and icons.  
   * This method simply resets the GUI status bar to the previous message it was 
   * displaying before the user hovered the current element.
   *
   * @see pwlib.gui#statusShow The method used for displaying the message in the 
   * GUI status bar.
   */
  this.item_mouseout = function () {
    _self.statusShow(-1);
  };

  /**
   * Show a message in the status bar.
   *
   * @param {String|Number} msg The message ID you want to display. The ID 
   * should be available in the {@link PaintWeb.lang.status} object. If the 
   * value is -1 then the previous non-temporary message will be displayed. If 
   * the ID is not available in the language file, then the string is shown 
   * as-is.
   *
   * @param {Boolean} [temporary=false] Tells if the message is temporary or 
   * not.
   */
  this.statusShow = function (msg, temporary) {
    var elem = this.elems.statusMessage;
    if (msg === -1 && elem._prevText === false) {
      return false;
    }

    if (msg === -1) {
      msg = elem._prevText;
    }

    if (msg in lang.status) {
      msg = lang.status[msg];
    }

    if (!temporary) {
      elem._prevText = msg;
    }

    if (elem.firstChild) {
      elem.removeChild(elem.firstChild);
    }

    win.status = msg;

    if (msg) {
      elem.appendChild(doc.createTextNode(msg));
    }
  };

  /**
   * The "About" command. This method displays the "About" panel.
   */
  this.commandAbout = function () {
    _self.floatingPanels.about.toggle();
  };

  /**
   * The <code>click</code> event handler for the tool DOM elements.
   *
   * @private
   *
   * @param {Event} ev The DOM Event object.
   *
   * @see PaintWeb#toolActivate to activate a drawing tool.
   */
  this.toolClick = function (ev) {
    app.toolActivate(this.parentNode.getAttribute('data-pwTool'), ev);
    ev.preventDefault();
  };

  /**
   * The <code>toolActivate</code> application event handler. This method 
   * provides visual feedback for the activation of a new drawing tool.
   *
   * @private
   *
   * @param {pwlib.appEvent.toolActivate} ev The application event object.
   *
   * @see PaintWeb#toolActivate the method which allows you to activate 
   * a drawing tool.
   */
  this.toolActivate = function (ev) {
    var tabAnchor,
        tabActive = _self.tools[ev.id],
        tabConfig = _self.toolTabConfig[ev.id] || {},
        tabPanel = _self.tabPanels.main,
        lineTab = tabPanel.tabs.line,
        shapeType = _self.inputs.shapeType,
        lineWidth = _self.inputs.line_lineWidth,
        lineCap = _self.inputs.line_lineCap,
        lineJoin = _self.inputs.line_lineJoin,
        miterLimit = _self.inputs.line_miterLimit,
        lineWidthLabel = null;

    tabActive.className += ' ' + _self.classPrefix + 'toolActive';
    try {
      tabActive.firstChild.focus();
    } catch (err) { }

    if ((ev.id + 'Active') in lang.status) {
      _self.statusShow(ev.id + 'Active');
    }

    // show/hide the shapeType input config.
    if (shapeType) {
      if (tabConfig.shapeType) {
        shapeType.style.display = '';
      } else {
        shapeType.style.display = 'none';
      }
    }

    if (ev.prevId) {
      var prevTab = _self.tools[ev.prevId],
          prevTabConfig = _self.toolTabConfig[ev.prevId] || {};

      prevTab.className = prevTab.className.
        replace(' ' + _self.classPrefix + 'toolActive', '');

      // hide the line tab
      if (prevTabConfig.lineTab && lineTab) {
        tabPanel.tabHide('line');
        lineTab.container.className = lineTab.container.className.
          replace(' ' + _self.classPrefix + 'main_line_' + ev.prevId, 
              ' ' + _self.classPrefix + 'main_line');
      }

      // hide the tab for the current tool.
      if (ev.prevId in tabPanel.tabs) {
        tabPanel.tabHide(ev.prevId);
      }
    }

    // Change the label of the lineWidth input element.
    if (tabConfig.lineWidthLabel) {
      lineWidthLabel = lineWidth.parentNode;
      lineWidthLabel.replaceChild(doc.createTextNode(tabConfig.lineWidthLabel), 
          lineWidthLabel.firstChild);

    }

    if (lineJoin) {
      if (tabConfig.lineJoin) {
        lineJoin.style.display = '';
      } else {
        lineJoin.style.display = 'none';
      }
    }

    if (lineCap) {
      if (tabConfig.lineCap) {
        lineCap.style.display = '';
      } else {
        lineCap.style.display = 'none';
      }
    }

    if (miterLimit) {
      if (tabConfig.miterLimit) {
        miterLimit.parentNode.parentNode.style.display = '';
      } else {
        miterLimit.parentNode.parentNode.style.display = 'none';
      }
    }

    if (lineWidth) {
      if (tabConfig.lineWidth) {
        lineWidth.parentNode.parentNode.style.display = '';
      } else {
        lineWidth.parentNode.parentNode.style.display = 'none';
      }
    }

    // show the line tab, if configured
    if (tabConfig.lineTab && 'line' in tabPanel.tabs) {
      tabAnchor = lineTab.button.firstChild;
      tabAnchor.title = tabConfig.lineTabLabel || lang.tabs.main[ev.id];
      tabAnchor.replaceChild(doc.createTextNode(tabAnchor.title), 
          tabAnchor.firstChild);

      if (ev.id !== 'line') {
        lineTab.container.className = lineTab.container.className.
            replace(' ' + _self.classPrefix + 'main_line', ' ' + _self.classPrefix 
                + 'main_line_' + ev.id);
      }

      tabPanel.tabShow('line');
    }

    // show the tab for the current tool, if there's one.
    if (ev.id in tabPanel.tabs) {
      tabPanel.tabShow(ev.id);
    }
  };

  /**
   * The <code>toolRegister</code> application event handler. This method adds 
   * the new tool into the GUI.
   *
   * @private
   *
   * @param {pwlib.appEvent.toolRegister} ev The application event object.
   *
   * @see PaintWeb#toolRegister the method which allows you to register new 
   * tools.
   */
  this.toolRegister = function (ev) {
    var attr = null, elem = null, anchor = null;

    if (ev.id in _self.tools) {
      elem = _self.tools[ev.id];
      attr = elem.getAttribute('data-pwTool');
      if (attr && attr !== ev.id) {
        attr = null;
        elem = null;
        delete _self.tools[ev.id];
      }
    }

    // Create a new element if there's none already associated to the tool ID.
    if (!elem) {
      elem = doc.createElement('li');
    }

    if (!attr) {
      elem.setAttribute('data-pwTool', ev.id);
    }

    elem.className += ' ' + _self.classPrefix + 'tool_' + ev.id;

    // Append an anchor element which holds the locale string.
    anchor = doc.createElement('a');
    anchor.title = lang.tools[ev.id];
    anchor.href = '#';
    anchor.appendChild(doc.createTextNode(anchor.title));

    if (elem.firstChild) {
      elem.replaceChild(anchor, elem.firstChild);
    } else {
      elem.appendChild(anchor);
    }

    anchor.addEventListener('click',     _self.toolClick,      false);
    anchor.addEventListener('mouseover', _self.item_mouseover, false);
    anchor.addEventListener('mouseout',  _self.item_mouseout,  false);

    if (!(ev.id in _self.tools)) {
      _self.tools[ev.id] = elem;
      _self.elems.tools.appendChild(elem);
    }

    // Disable the text tool icon if the Canvas Text API is not supported.
    if (ev.id === 'text' && !app.layer.context.fillText && 
        !app.layer.context.mozPathText && elem) {
      elem.className += ' ' + _self.classPrefix + 'disabled';
      anchor.title = lang.tools.textUnsupported;

      anchor.removeEventListener('click', _self.toolClick, false);
      anchor.addEventListener('click', function (ev) {
        ev.preventDefault();
      }, false);
    }
  };

  /**
   * The <code>toolUnregister</code> application event handler. This method the 
   * tool element from the GUI.
   *
   * @param {pwlib.appEvent.toolUnregister} ev The application event object.
   *
   * @see PaintWeb#toolUnregister the method which allows you to unregister 
   * tools.
   */
  this.toolUnregister = function (ev) {
    if (ev.id in _self.tools) {
      _self.elems.tools.removeChild(_self.tools[ev.id]);
      delete _self.tools[ev.id];
    } else {
      return;
    }
  };

  /**
   * The <code>click</code> event handler for the command DOM elements.
   *
   * @private
   *
   * @param {Event} ev The DOM Event object.
   *
   * @see PaintWeb#commandRegister to register a new command.
   */
  this.commandClick = function (ev) {
    var cmd = this.parentNode.getAttribute('data-pwCommand');
    if (cmd && cmd in app.commands) {
      app.commands[cmd].call(this, ev);
    }
    ev.preventDefault();

    try {
      this.focus();
    } catch (err) { }
  };

  /**
   * The <code>commandRegister</code> application event handler. GUI elements 
   * associated to commands are updated to ensure proper user interaction.
   *
   * @private
   *
   * @param {pwlib.appEvent.commandRegister} ev The application event object.
   *
   * @see PaintWeb#commandRegister the method which allows you to register new 
   * commands.
   */
  this.commandRegister = function (ev) {
    var elem   = _self.commands[ev.id],
        anchor = null;
    if (!elem) {
      return;
    }

    elem.className += ' ' + _self.classPrefix + 'cmd_' + ev.id;

    anchor = doc.createElement('a');
    anchor.title = lang.commands[ev.id];
    anchor.href = '#';
    anchor.appendChild(doc.createTextNode(anchor.title));

    // Remove the text content and append the locale string associated to 
    // current command inside an anchor element (for better keyboard 
    // accessibility).
    if (elem.firstChild) {
      elem.removeChild(elem.firstChild);
    }
    elem.appendChild(anchor);

    anchor.addEventListener('click',     _self.commandClick,   false);
    anchor.addEventListener('mouseover', _self.item_mouseover, false);
    anchor.addEventListener('mouseout',  _self.item_mouseout,  false);
  };

  /**
   * The <code>commandUnregister</code> application event handler. This method 
   * simply removes all the user interactivity from the GUI element associated 
   * to the command being unregistered.
   *
   * @private
   *
   * @param {pwlib.appEvent.commandUnregister} ev The application event object.
   *
   * @see PaintWeb#commandUnregister the method which allows you to unregister 
   * commands.
   */
  this.commandUnregister = function (ev) {
    var elem   = _self.commands[ev.id],
        anchor = null;
    if (!elem) {
      return;
    }

    elem.className = elem.className.replace(' ' + _self.classPrefix + 'cmd_' 
        + ev.id, '');

    anchor = elem.firstChild;
    anchor.removeEventListener('click',     this.commands[ev.id], false);
    anchor.removeEventListener('mouseover', _self.item_mouseover, false);
    anchor.removeEventListener('mouseout',  _self.item_mouseout,  false);

    elem.removeChild(anchor);
  };

  /**
   * The <code>historyUpdate</code> application event handler. GUI elements 
   * associated to the <code>historyUndo</code> and to the 
   * <code>historyRedo</code> commands are updated such that they are either 
   * enabled or disabled, depending on the current history position.
   *
   * @private
   *
   * @param {pwlib.appEvent.historyUpdate} ev The application event object.
   *
   * @see PaintWeb#historyGoto the method which allows you to go to different 
   * history states.
   */
  this.historyUpdate = function (ev) {
    var undoElem  = _self.commands.historyUndo,
        undoState = false,
        redoElem  = _self.commands.historyRedo,
        redoState = false,
        className = ' ' + _self.classPrefix + 'disabled',
        undoElemState = undoElem.className.indexOf(className) === -1,
        redoElemState = redoElem.className.indexOf(className) === -1;

    if (ev.currentPos > 1) {
      undoState = true;
    }
    if (ev.currentPos < ev.states) {
      redoState = true;
    }

    if (undoElemState !== undoState) {
      if (undoState) {
        undoElem.className = undoElem.className.replace(className, '');
      } else {
        undoElem.className += className;
      }
    }

    if (redoElemState !== redoState) {
      if (redoState) {
        redoElem.className = redoElem.className.replace(className, '');
      } else {
        redoElem.className += className;
      }
    }
  };

  /**
   * The <code>imageSizeChange</code> application event handler. The GUI element 
   * which displays the image dimensions is updated to display the new image 
   * size.
   *
   * <p>Image size refers strictly to the dimensions of the image being edited 
   * by the user, that's width and height.
   *
   * @private
   * @param {pwlib.appEvent.imageSizeChange} ev The application event object.
   */
  this.imageSizeChange = function (ev) {
    var imageSize  = _self.elems.imageSize;
    if (imageSize) {
      imageSize.replaceChild(doc.createTextNode(ev.width + 'x' + ev.height), 
          imageSize.firstChild);
    }
  };

  /**
   * The <code>canvasSizeChange</code> application event handler. The Canvas 
   * container element dimensions are updated to the new values, and the image 
   * resize handle is positioned accordingly.
   *
   * <p>Canvas size refers strictly to the dimensions of the Canvas elements in 
   * the browser, changed with CSS style properties, width and height. Scaling 
   * of the Canvas elements is applied when the user zooms the image or when the 
   * browser changes the render DPI / zoom.
   *
   * @private
   * @param {pwlib.appEvent.canvasSizeChange} ev The application event object.
   */
  this.canvasSizeChange = function (ev) {
    var canvasContainer = _self.elems.canvasContainer,
        canvasResizer   = _self.canvasResizer,
        className       = ' ' + _self.classPrefix + 'disabled',
        resizeHandle    = canvasResizer.resizeHandle;

    // Update the Canvas container to be the same size as the Canvas elements.
    canvasContainer.style.width  = ev.width  + 'px';
    canvasContainer.style.height = ev.height + 'px';

    resizeHandle.style.top  = ev.height + 'px';
    resizeHandle.style.left = ev.width  + 'px';
  };

  /**
   * The <code>imageZoom</code> application event handler. The GUI input element 
   * which displays the image zoom level is updated to display the new value.
   *
   * @private
   * @param {pwlib.appEvent.imageZoom} ev The application event object.
   */
  this.imageZoom = function (ev) {
    var elem  = _self.inputs.imageZoom,
        val   = MathRound(ev.zoom * 100);
    if (elem && elem.value != val) {
      elem.value = val;
    }
  };

  /**
   * The <code>configChange</code> application event handler. This method 
   * ensures the GUI input elements stay up-to-date when some PaintWeb 
   * configuration is modified.
   *
   * @private
   * @param {pwlib.appEvent.configChange} ev The application event object.
   */
  this.configChangeHandler = function (ev) {
    var cfg = '', input;
    if (ev.group) {
      cfg = ev.group.replace('.', '_') + '_';
    }
    cfg += ev.config;
    input = _self.inputs[cfg];

    // Handle changes for color inputs.
    if (!input && (input = _self.colorInputs[cfg])) {
      var color = ev.value.replace(/\s+/g, '').
                    replace(/^rgba\(/, '').replace(/\)$/, '');

      color = color.split(',');
      input.updateColor({
        red:   color[0] / 255,
        green: color[1] / 255,
        blue:  color[2] / 255,
        alpha: color[3]
      });

      return;
    }

    if (!input) {
      return;
    }

    var tag = input.tagName.toLowerCase(),
        isInput = tag === 'select' || tag === 'input' || tag === 'textarea';

    if (isInput) {
      if (input.type === 'checkbox' && input.checked !== ev.value) {
        input.checked = ev.value;
      }
      if (input.type !== 'checkbox' && input.value !== ev.value) {
        input.value = ev.value;
      }

      return;
    }

    var classActive = ' ' + _self.className + 'configActive';

    if (input.hasAttribute('data-pwConfigToggle')) {
      var inputActive = input.className.indexOf(classActive) !== -1;

      if (ev.value && !inputActive) {
        input.className += classActive;
      } else if (!ev.value && inputActive) {
        input.className = input.className.replace(classActive, '');
      }
    }

    var classActive = ' ' + _self.className + 'configActive',
        prevValElem = _self.inputValues[cfg + '_' + ev.previousValue],
        valElem = _self.inputValues[cfg + '_' + ev.value];

    if (prevValElem && prevValElem.className.indexOf(classActive) !== -1) {
      prevValElem.className = prevValElem.className.replace(classActive, '');
    }

    if (valElem && valElem.className.indexOf(classActive) === -1) {
      valElem.className += classActive;
    }
  };

  /**
   * The <code>click</code> event handler for DOM elements associated to 
   * PaintWeb configuration values. These elements rely on parent elements which 
   * are associated to configuration properties.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.configChange} event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.configValueClick = function (ev) {
    var pNode = this.parentNode,
        input = pNode._pwConfigParent,
        val = pNode.getAttribute('data-pwConfigValue');

    if (!input || !input._pwConfigProperty) {
      return;
    }

    ev.preventDefault();

    var className = ' ' + _self.classPrefix + 'configActive',
        groupRef = input._pwConfigGroupRef,
        group = input._pwConfigGroup,
        prop = input._pwConfigProperty,
        prevVal = groupRef[prop],
        prevValElem = _self.inputValues[group.replace('.', '_') + '_' + prop 
          + '_' + prevVal];

    if (prevVal == val) {
      return;
    }

    if (prevValElem && prevValElem.className.indexOf(className) !== -1) {
      prevValElem.className = prevValElem.className.replace(className, '');
    }

    groupRef[prop] = val;

    if (pNode.className.indexOf(className) === -1) {
      pNode.className += className;
    }

    app.events.dispatch(new appEvent.configChange(val, prevVal, prop, group, 
          groupRef));
  };

  /**
   * The <code>change</code> event handler for input elements associated to 
   * PaintWeb configuration properties.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.configChange} event.
   *
   * @private
   */
  this.configInputChange = function () {
    if (!this._pwConfigProperty) {
      return;
    }

    var val = this.type === 'checkbox' ? this.checked : this.value,
        groupRef = this._pwConfigGroupRef,
        group = this._pwConfigGroup,
        prop = this._pwConfigProperty,
        prevVal = groupRef[prop];

    if (this.getAttribute('type') === 'number') {
      val = parseInt(val);
      if (val != this.value) {
        this.value = val;
      }
    }

    if (val == prevVal) {
      return;
    }

    groupRef[prop] = val;

    app.events.dispatch(new appEvent.configChange(val, prevVal, prop, group, 
          groupRef));
  };

  /**
   * The <code>click</code> event handler for DOM elements associated to boolean 
   * configuration properties. These elements only toggle the true/false value 
   * of the configuration property.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.configChange} event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  this.configToggleClick = function (ev) {
    var className = ' ' + _self.classPrefix + 'configActive',
        pNode = this.parentNode,
        groupRef = pNode._pwConfigGroupRef,
        group = pNode._pwConfigGroup,
        prop = pNode._pwConfigProperty,
        elemActive = pNode.className.indexOf(className) !== -1;

    ev.preventDefault();

    groupRef[prop] = !groupRef[prop];

    if (groupRef[prop] && !elemActive) {
      pNode.className += className;
    } else if (!groupRef[prop] && elemActive) {
      pNode.className = pNode.className.replace(className, '');
    }

    app.events.dispatch(new appEvent.configChange(groupRef[prop], 
          !groupRef[prop], prop, group, groupRef));
  };

  /**
   * The <code>shadowAllow</code> application event handler. This method 
   * shows/hide the shadow tab when shadows are allowed/disallowed.
   *
   * @private
   * @param {pwlib.appEvent.shadowAllow} ev The application event object.
   */
  this.shadowAllow = function (ev) {
    if ('shadow' in _self.tabPanels.main.tabs) {
      if (ev.allowed) {
        _self.tabPanels.main.tabShow('shadow');
      } else {
        _self.tabPanels.main.tabHide('shadow');
      }
    }
  };

  /**
   * The <code>clipboardUpdate</code> application event handler. The GUI element 
   * associated to the <code>clipboardPaste</code> command is updated to be 
   * disabled/enabled depending on the event.
   *
   * @private
   * @param {pwlib.appEvent.clipboardUpdate} ev The application event object.
   */
  this.clipboardUpdate = function (ev) {
    var classDisabled = ' ' + _self.classPrefix + 'disabled',
        elem, elemEnabled,
        elems = [_self.commands.clipboardPaste, 
        _self.elems.selTab_clipboardPaste];

    for (var i = 0, n = elems.length; i < n; i++) {
      elem = elems[i];
      if (!elem) {
        continue;
      }

      elemEnabled = elem.className.indexOf(classDisabled) === -1;

      if (!ev.data && elemEnabled) {
        elem.className += classDisabled;
      } else if (ev.data && !elemEnabled) {
        elem.className = elem.className.replace(classDisabled, '');
      }
    }
  };

  /**
   * The <code>selectionChange</code> application event handler. The GUI 
   * elements associated to the <code>selectionCut</code> and 
   * <code>selectionCopy</code> commands are updated to be disabled/enabled 
   * depending on the event.
   *
   * @private
   * @param {pwlib.appEvent.selectionChange} ev The application event object.
   */
  this.selectionChange = function (ev) {
    var classDisabled  = ' ' + _self.classPrefix + 'disabled',
        elem, elemEnabled,
        elems = [_self.commands.selectionCut, _self.commands.selectionCopy, 
        _self.elems.selTab_selectionCut, _self.elems.selTab_selectionCopy, 
        _self.commands.selectionDelete, _self.commands.selectionFill, 
        _self.commands.selectionCrop];

    for (var i = 0, n = elems.length; i < n; i++) {
      elem = elems[i];
      if (!elem) {
        continue;
      }

      elemEnabled = elem.className.indexOf(classDisabled) === -1;

      if (ev.state === ev.STATE_NONE && elemEnabled) {
        elem.className += classDisabled;
      } else if (ev.state === ev.STATE_SELECTED && !elemEnabled) {
        elem.className = elem.className.replace(classDisabled, '');
      }
    }
  };

  /**
   * Show the graphical user interface.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.guiShow} application 
   * event.
   */
  this.show = function () {
    var placeholder = config.guiPlaceholder,
        className   = this.classPrefix + 'placeholder',
        re          = new RegExp('\\b' + className);

    if (!re.test(placeholder.className)) {
      placeholder.className += ' ' + className;
    }

    try {
      placeholder.focus();
    } catch (err) { }

    app.events.dispatch(new appEvent.guiShow());
  };

  /**
   * Hide the graphical user interface.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.guiHide} application 
   * event.
   */
  this.hide = function () {
    var placeholder = config.guiPlaceholder,
        re = new RegExp('\\b' + this.classPrefix + 'placeholder', 'g');

    placeholder.className = placeholder.className.replace(re, '');

    app.events.dispatch(new appEvent.guiHide());
  };

  /**
   * The application destroy event handler. This method is invoked by the main 
   * PaintWeb application when the instance is destroyed, for the purpose of 
   * cleaning-up the GUI-related things from the document add by the current 
   * instance.
   *
   * @private
   */
  this.destroy = function () {
    var placeholder = config.guiPlaceholder;

    while(placeholder.hasChildNodes()) {
      placeholder.removeChild(placeholder.firstChild);
    }
  };

  /**
   * Resize the PaintWeb graphical user interface.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.configChange} event for 
   * the "viewportWidth" and "viewportHeight" configuration properties. Both 
   * properties are updated to hold the new values you give.
   *
   * <p>Once the GUI is resized, the {@link pwlib.appEvent.viewportSizeChange} 
   * event is also dispatched.
   *
   * @param {String} width The new width you want. Make sure the value is a CSS 
   * length, like "50%", "450px" or "30em".
   *
   * @param {String} height The new height you want.
   */
  this.resizeTo = function (width, height) {
    if (!width || !height) {
      return;
    }

    var width_old  = config.viewportWidth,
        height_old = config.viewportHeight;

    config.viewportWidth  = width;
    config.viewportHeight = height;

    app.events.dispatch(new appEvent.configChange(width, width_old, 
          'viewportWidth', '', config));

    app.events.dispatch(new appEvent.configChange(height, height_old, 
          'viewportHeight', '', config));

    config.guiPlaceholder.style.width = config.viewportWidth;
    this.elems.viewport.style.height  = config.viewportHeight;

    app.events.dispatch(new appEvent.viewportSizeChange(width, height));
  };

  /**
   * The state change event handler for the Hand tool. This function 
   * enables/disables the Hand tool by checking if the current image fits into 
   * the viewport or not.
   *
   * <p>This function is invoked when one of the following application events is  
   * dispatched: <code>viewportSizeChange</code>, <code>canvasSizeChange</code> 
   * or <code>appInit</code.
   *
   * @private
   * @param 
   * {pwlib.appEvent.viewportSizeChange|pwlib.appEvent.canvasSizeChange|pwlib.appEvent.appInit} 
   * [ev] The application event object.
   */
  this.toolHandStateChange = function (ev) {
    var cwidth    = 0,
        cheight   = 0,
        className = ' ' + _self.classPrefix + 'disabled',
        hand      = _self.tools.hand,
        viewport  = _self.elems.viewport;

    if (!hand) {
      return;
    }

    if (ev.type === 'canvasSizeChange') {
      cwidth  = ev.width;
      cheight = ev.height;
    } else {
      var containerStyle = _self.elems.canvasContainer.style;
      cwidth  = parseInt(containerStyle.width);
      cheight = parseInt(containerStyle.height);
    }

    // FIXME: it should be noted that when PaintWeb loads, the entire GUI is 
    // hidden, and win.getComputedStyle() style tells that the viewport 
    // width/height is 0.
    cs = win.getComputedStyle(viewport, null);

    var vwidth     = parseInt(cs.width),
        vheight    = parseInt(cs.height),
        enableHand = false,
        handState  = hand.className.indexOf(className) === -1;

    if (vheight < cheight || vwidth < cwidth) {
      enableHand = true;
    }

    if (enableHand && !handState) {
      hand.className = hand.className.replace(className, '');
    } else if (!enableHand && handState) {
      hand.className += className;
    }

    if (!enableHand && app.tool && app.tool._id === 'hand' && 'prevTool' in 
        app.tool) {
      app.toolActivate(app.tool.prevTool, ev);
    }
  };
};

/**
 * @class A floating panel GUI element.
 *
 * @private
 *
 * @param {pwlib.gui} gui Reference to the PaintWeb GUI object.
 *
 * @param {Element} container Reference to the DOM element you want to transform 
 * into a floating panel.
 */
pwlib.guiFloatingPanel = function (gui, container) {
  var _self          = this,
      appEvent       = pwlib.appEvent,
      cStyle         = container.style,
      doc            = gui.app.doc,
      guiPlaceholder = gui.app.config.guiPlaceholder,
      lang           = gui.app.lang,
      panels         = gui.floatingPanels,
      win            = gui.app.win,
      zIndex_step    = 200;

  // These hold the mouse starting location during the drag operation.
  var mx, my;

  // These hold the panel starting location during the drag operation.
  var ptop, pleft;

  /**
   * Panel state: hidden.
   * @constant
   */
  this.STATE_HIDDEN    = 0;

  /**
   * Panel state: visible.
   * @constant
   */
  this.STATE_VISIBLE   = 1;

  /**
   * Panel state: minimized.
   * @constant
   */
  this.STATE_MINIMIZED = 3;

  /**
   * Panel state: the user is dragging the floating panel.
   * @constant
   */
  this.STATE_DRAGGING  = 4;

  /**
   * Tells the state of the floating panel: hidden/minimized/visible or if it's 
   * being dragged.
   * @type Number
   */
  this.state = -1;

  /**
   * Floating panel ID. This is the ID used in the 
   * <var>data-pwFloatingPanel</var> element attribute.
   * @type String
   */
  this.id = null;

  /**
   * Reference to the floating panel element.
   * @type Element
   */
  this.container = container;

  /**
   * The viewport element. This element is the first parent element which has 
   * the style.overflow set to "auto" or "scroll".
   * @type Element
   */
  this.viewport = null;

  /**
   * Custom application events interface.
   * @type pwlib.appEvents
   */
  this.events = null;

  /**
   * The panel content element.
   * @type Element
   */
  this.content = null;

  // The initial viewport scroll position.
  var vScrollLeft = 0, vScrollTop = 0,
      btn_close = null, btn_minimize = null;

  /**
   * Initialize the floating panel.
   * @private
   */
  function init () {
    _self.events = new pwlib.appEvents(_self);

    _self.id = _self.container.getAttribute('data-pwFloatingPanel');

    var ttl = _self.container.getElementsByTagName('h1')[0],
        content = _self.container.getElementsByTagName('div')[0],
        cs = win.getComputedStyle(_self.container, null),
        zIndex = parseInt(cs.zIndex);

    cStyle.zIndex = cs.zIndex;

    if (zIndex > panels.zIndex_) {
      panels.zIndex_ = zIndex;
    }

    _self.container.className += ' ' + gui.classPrefix + 'floatingPanel ' +
      gui.classPrefix + 'floatingPanel_' + _self.id;

    // the content
    content.className += ' ' + gui.classPrefix + 'floatingPanel_content';
    _self.content = content;

    // setup the title element
    ttl.className += ' ' + gui.classPrefix + 'floatingPanel_title';
    ttl.replaceChild(doc.createTextNode(lang.floatingPanels[_self.id]), 
        ttl.firstChild);

    ttl.addEventListener('mousedown', ev_mousedown, false);

    // allow auto-hide for the panel
    if (_self.container.getAttribute('data-pwPanelHide') === 'true') {
      _self.hide();
    } else {
      _self.state = _self.STATE_VISIBLE;
    }

    // Find the viewport parent element.
    var pNode = _self.container.parentNode,
        found = null;

    while (!found && pNode) {
      if (pNode.nodeName.toLowerCase() === 'html') {
        found = pNode;
        break;
      }

      cs = win.getComputedStyle(pNode, null);
      if (cs && (cs.overflow === 'scroll' || cs.overflow === 'auto')) {
        found = pNode;
      } else {
        pNode = pNode.parentNode;
      }
    }

    _self.viewport = found;

    // add the panel minimize button.
    btn_minimize = doc.createElement('a');
    btn_minimize.href = '#';
    btn_minimize.title = lang.floatingPanelMinimize;
    btn_minimize.className = gui.classPrefix + 'floatingPanel_minimize';
    btn_minimize.addEventListener('click', ev_minimize, false);
    btn_minimize.appendChild(doc.createTextNode(btn_minimize.title));

    _self.container.insertBefore(btn_minimize, content);

    // add the panel close button.
    btn_close = doc.createElement('a');
    btn_close.href = '#';
    btn_close.title = lang.floatingPanelClose;
    btn_close.className = gui.classPrefix + 'floatingPanel_close';
    btn_close.addEventListener('click', ev_close, false);
    btn_close.appendChild(doc.createTextNode(btn_close.title));

    _self.container.insertBefore(btn_close, content);

    // setup the panel resize handle.
    if (_self.container.getAttribute('data-pwPanelResizable') === 'true') {
      var resizeHandle = doc.createElement('div');
      resizeHandle.className = gui.classPrefix + 'floatingPanel_resizer';
      _self.container.appendChild(resizeHandle);
      _self.resizer = new pwlib.guiResizer(gui, resizeHandle, _self.container);
    }
  };

  /**
   * The <code>click</code> event handler for the panel Minimize button element.
   *
   * <p>This method dispatches the {@link 
   * pwlib.appEvent.guiFloatingPanelStateChange} application event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_minimize (ev) {
    ev.preventDefault();
    try {
      this.focus();
    } catch (err) { }

    var classMinimized = ' ' + gui.classPrefix + 'floatingPanel_minimized';

    if (_self.state === _self.STATE_MINIMIZED) {
      _self.state = _self.STATE_VISIBLE;

      this.title = lang.floatingPanelMinimize;
      this.className = gui.classPrefix + 'floatingPanel_minimize';
      this.replaceChild(doc.createTextNode(this.title), this.firstChild);

      if (_self.container.className.indexOf(classMinimized) !== -1) {
        _self.container.className 
          = _self.container.className.replace(classMinimized, '');
      }

    } else if (_self.state === _self.STATE_VISIBLE) {
      _self.state = _self.STATE_MINIMIZED;

      this.title = lang.floatingPanelRestore;
      this.className = gui.classPrefix + 'floatingPanel_restore';
      this.replaceChild(doc.createTextNode(this.title), this.firstChild);

      if (_self.container.className.indexOf(classMinimized) === -1) {
        _self.container.className += classMinimized;
      }
    }

    _self.events.dispatch(new appEvent.guiFloatingPanelStateChange(_self.state));

    _self.bringOnTop();
  };

  /**
   * The <code>click</code> event handler for the panel Close button element.  
   * This hides the floating panel.
   *
   * <p>This method dispatches the {@link 
   * pwlib.appEvent.guiFloatingPanelStateChange} application event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_close (ev) {
    ev.preventDefault();
    _self.hide();
    try {
      guiPlaceholder.focus();
    } catch (err) { }
  };

  /**
   * The <code>mousedown</code> event handler. This is invoked when you start 
   * dragging the floating panel.
   *
   * <p>This method dispatches the {@link 
   * pwlib.appEvent.guiFloatingPanelStateChange} application event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_mousedown (ev) {
    _self.state = _self.STATE_DRAGGING;

    mx = ev.clientX;
    my = ev.clientY;

    var cs = win.getComputedStyle(_self.container, null);

    ptop  = parseInt(cs.top);
    pleft = parseInt(cs.left);

    if (_self.viewport) {
      vScrollLeft = _self.viewport.scrollLeft;
      vScrollTop  = _self.viewport.scrollTop;
    }

    _self.bringOnTop();

    doc.addEventListener('mousemove', ev_mousemove, false);
    doc.addEventListener('mouseup',   ev_mouseup,   false);

    _self.events.dispatch(new appEvent.guiFloatingPanelStateChange(_self.state));

    if (ev.preventDefault) {
      ev.preventDefault();
    }
  };

  /**
   * The <code>mousemove</code> event handler. This performs the actual move of 
   * the floating panel.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_mousemove (ev) {
    var x = pleft + ev.clientX - mx,
        y = ptop  + ev.clientY - my;

    if (_self.viewport) {
      if (_self.viewport.scrollLeft !== vScrollLeft) {
        x += _self.viewport.scrollLeft - vScrollLeft;
      }
      if (_self.viewport.scrollTop !== vScrollTop) {
        y += _self.viewport.scrollTop - vScrollTop;
      }
    }

    cStyle.left = x + 'px';
    cStyle.top  = y + 'px';
  };

  /**
   * The <code>mouseup</code> event handler. This ends the panel drag operation.
   *
   * <p>This method dispatches the {@link 
   * pwlib.appEvent.guiFloatingPanelStateChange} application event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_mouseup (ev) {
    if (_self.container.className.indexOf(' ' + gui.classPrefix 
          + 'floatingPanel_minimized') !== -1) {
      _self.state = _self.STATE_MINIMIZED;
    } else {
      _self.state = _self.STATE_VISIBLE;
    }

    doc.removeEventListener('mousemove', ev_mousemove, false);
    doc.removeEventListener('mouseup',   ev_mouseup,   false);

    try {
      guiPlaceholder.focus();
    } catch (err) { }

    _self.events.dispatch(new appEvent.guiFloatingPanelStateChange(_self.state));
  };

  /**
   * Bring the panel to the top. This method makes sure the current floating 
   * panel is visible.
   */
  this.bringOnTop = function () {
    panels.zIndex_ += zIndex_step;
    cStyle.zIndex = panels.zIndex_;
  };

  /**
   * Hide the panel.
   *
   * <p>This method dispatches the {@link 
   * pwlib.appEvent.guiFloatingPanelStateChange} application event.
   */
  this.hide = function () {
    cStyle.display = 'none';
    _self.state = _self.STATE_HIDDEN;
    _self.events.dispatch(new appEvent.guiFloatingPanelStateChange(_self.state));
  };

  /**
   * Show the panel.
   *
   * <p>This method dispatches the {@link 
   * pwlib.appEvent.guiFloatingPanelStateChange} application event.
   */
  this.show = function () {
    if (_self.state === _self.STATE_VISIBLE) {
      return;
    }

    cStyle.display = 'block';
    _self.state = _self.STATE_VISIBLE;

    var classMinimized = ' ' + gui.classPrefix + 'floatingPanel_minimized';

    if (_self.container.className.indexOf(classMinimized) !== -1) {
      _self.container.className 
        = _self.container.className.replace(classMinimized, '');

      btn_minimize.className = gui.classPrefix + 'floatingPanel_minimize';
      btn_minimize.title = lang.floatingPanelMinimize;
      btn_minimize.replaceChild(doc.createTextNode(btn_minimize.title), 
          btn_minimize.firstChild);
    }

    _self.events.dispatch(new appEvent.guiFloatingPanelStateChange(_self.state));

    _self.bringOnTop();
  };

  /**
   * Toggle the panel visibility.
   *
   * <p>This method dispatches the {@link 
   * pwlib.appEvent.guiFloatingPanelStateChange} application event.
   */
  this.toggle = function () {
    if (_self.state === _self.STATE_VISIBLE || _self.state === 
        _self.STATE_MINIMIZED) {
      _self.hide();
    } else {
      _self.show();
    }
  };

  init();
};

/**
 * @class The state change event for the floating panel. This event is fired 
 * when the floating panel changes its state. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} state The floating panel state.
 */
pwlib.appEvent.guiFloatingPanelStateChange = function (state) {
  /**
   * Panel state: hidden.
   * @constant
   */
  this.STATE_HIDDEN    = 0;

  /**
   * Panel state: visible.
   * @constant
   */
  this.STATE_VISIBLE   = 1;

  /**
   * Panel state: minimized.
   * @constant
   */
  this.STATE_MINIMIZED = 3;

  /**
   * Panel state: the user is dragging the floating panel.
   * @constant
   */
  this.STATE_DRAGGING  = 4;

  /**
   * The current floating panel state.
   * @type Number
   */
  this.state = state;

  pwlib.appEvent.call(this, 'guiFloatingPanelStateChange');
};

/**
 * @class Resize handler.
 *
 * @private
 *
 * @param {pwlib.gui} gui Reference to the PaintWeb GUI object.
 *
 * @param {Element} resizeHandle Reference to the resize handle DOM element.  
 * This is the element users will be able to drag to achieve the resize effect 
 * on the <var>container</var> element.
 *
 * @param {Element} container Reference to the container DOM element. This is 
 * the element users will be able to resize using the <var>resizeHandle</var> 
 * element.
 */
pwlib.guiResizer = function (gui, resizeHandle, container) {
  var _self              = this,
      cStyle             = container.style,
      doc                = gui.app.doc,
      guiResizeEnd       = pwlib.appEvent.guiResizeEnd,
      guiResizeMouseMove = pwlib.appEvent.guiResizeMouseMove,
      guiResizeStart     = pwlib.appEvent.guiResizeStart,
      win                = gui.app.win;

  /**
   * Custom application events interface.
   * @type pwlib.appEvents
   */
  this.events = null;

  /**
   * The resize handle DOM element.
   * @type Element
   */
  this.resizeHandle = resizeHandle;

  /**
   * The container DOM element. This is the element that's resized by the user 
   * when he/she drags the resize handle.
   * @type Element
   */
  this.container = container;

  /**
   * The viewport element. This element is the first parent element which has 
   * the style.overflow set to "auto" or "scroll".
   * @type Element
   */
  this.viewport = null;

  /**
   * Tells if the GUI resizer should dispatch the {@link 
   * pwlib.appEvent.guiResizeMouseMove} application event when the user moves 
   * the mouse during the resize operation.
   *
   * @type Boolean
   * @default false
   */
  this.dispatchMouseMove = false;

  /**
   * Tells if the user resizing the container now.
   *
   * @type Boolean
   * @default false
   */
  this.resizing = false;

  // The initial position of the mouse.
  var mx = 0, my = 0;

  // The initial container dimensions.
  var cWidth = 0, cHeight = 0;

  // The initial viewport scroll position.
  var vScrollLeft = 0, vScrollTop = 0;

  /**
   * Initialize the resize functionality.
   * @private
   */
  function init () {
    _self.events = new pwlib.appEvents(_self);
    resizeHandle.addEventListener('mousedown', ev_mousedown, false);

    // Find the viewport parent element.
    var cs, pNode = _self.container.parentNode,
        found = null;
    while (!found && pNode) {
      if (pNode.nodeName.toLowerCase() === 'html') {
        found = pNode;
        break;
      }

      cs = win.getComputedStyle(pNode, null);
      if (cs && (cs.overflow === 'scroll' || cs.overflow === 'auto')) {
        found = pNode;
      } else {
        pNode = pNode.parentNode;
      }
    }

    _self.viewport = found;
  };

  /**
   * The <code>mousedown</code> event handler. This starts the resize operation.
   *
   * <p>This function dispatches the {@link pwlib.appEvent.guiResizeStart} 
   * event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_mousedown (ev) {
    mx = ev.clientX;
    my = ev.clientY;

    var cs = win.getComputedStyle(_self.container, null);
    cWidth  = parseInt(cs.width);
    cHeight = parseInt(cs.height);

    var cancel = _self.events.dispatch(new guiResizeStart(mx, my, cWidth, 
          cHeight));

    if (cancel) {
      return;
    }

    if (_self.viewport) {
      vScrollLeft = _self.viewport.scrollLeft;
      vScrollTop  = _self.viewport.scrollTop;
    }

    _self.resizing = true;
    doc.addEventListener('mousemove', ev_mousemove, false);
    doc.addEventListener('mouseup',   ev_mouseup,   false);

    if (ev.preventDefault) {
      ev.preventDefault();
    }

    if (ev.stopPropagation) {
      ev.stopPropagation();
    }
  };

  /**
   * The <code>mousemove</code> event handler. This performs the actual resizing 
   * of the <var>container</var> element.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_mousemove (ev) {
    var w = cWidth  + ev.clientX - mx,
        h = cHeight + ev.clientY - my;

    if (_self.viewport) {
      if (_self.viewport.scrollLeft !== vScrollLeft) {
        w += _self.viewport.scrollLeft - vScrollLeft;
      }
      if (_self.viewport.scrollTop !== vScrollTop) {
        h += _self.viewport.scrollTop - vScrollTop;
      }
    }

    cStyle.width  = w + 'px';
    cStyle.height = h + 'px';

    if (_self.dispatchMouseMove) {
      _self.events.dispatch(new guiResizeMouseMove(ev.clientX, ev.clientY, w, 
            h));
    }
  };

  /**
   * The <code>mouseup</code> event handler. This ends the resize operation.
   *
   * <p>This function dispatches the {@link pwlib.appEvent.guiResizeEnd} event.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_mouseup (ev) {
    var cancel = _self.events.dispatch(new guiResizeEnd(ev.clientX, ev.clientY, 
          parseInt(cStyle.width), parseInt(cStyle.height)));

    if (cancel) {
      return;
    }

    _self.resizing = false;
    doc.removeEventListener('mousemove', ev_mousemove, false);
    doc.removeEventListener('mouseup',   ev_mouseup,   false);
  };

  init();
};

/**
 * @class The GUI element resize start event. This event is cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} x The mouse location on the x-axis.
 * @param {Number} y The mouse location on the y-axis.
 * @param {Number} width The element width.
 * @param {Number} height The element height.
 */
pwlib.appEvent.guiResizeStart = function (x, y, width, height) {
  /**
   * The mouse location on the x-axis.
   * @type Number
   */
  this.x = x;

  /**
   * The mouse location on the y-axis.
   * @type Number
   */
  this.y = y;

  /**
   * The element width.
   * @type Number
   */
  this.width = width;

  /**
   * The element height.
   * @type Number
   */
  this.height = height;

  pwlib.appEvent.call(this, 'guiResizeStart', true);
};

/**
 * @class The GUI element resize end event. This event is cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} x The mouse location on the x-axis.
 * @param {Number} y The mouse location on the y-axis.
 * @param {Number} width The element width.
 * @param {Number} height The element height.
 */
pwlib.appEvent.guiResizeEnd = function (x, y, width, height) {
  /**
   * The mouse location on the x-axis.
   * @type Number
   */
  this.x = x;

  /**
   * The mouse location on the y-axis.
   * @type Number
   */
  this.y = y;

  /**
   * The element width.
   * @type Number
   */
  this.width = width;

  /**
   * The element height.
   * @type Number
   */
  this.height = height;

  pwlib.appEvent.call(this, 'guiResizeEnd', true);
};

/**
 * @class The GUI element resize mouse move event. This event is not cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {Number} x The mouse location on the x-axis.
 * @param {Number} y The mouse location on the y-axis.
 * @param {Number} width The element width.
 * @param {Number} height The element height.
 */
pwlib.appEvent.guiResizeMouseMove = function (x, y, width, height) {
  /**
   * The mouse location on the x-axis.
   * @type Number
   */
  this.x = x;

  /**
   * The mouse location on the y-axis.
   * @type Number
   */
  this.y = y;

  /**
   * The element width.
   * @type Number
   */
  this.width = width;

  /**
   * The element height.
   * @type Number
   */
  this.height = height;

  pwlib.appEvent.call(this, 'guiResizeMouseMove');
};

/**
 * @class The tabbed panel GUI component.
 *
 * @private
 *
 * @param {pwlib.gui} gui Reference to the PaintWeb GUI object.
 *
 * @param {Element} panel Reference to the panel DOM element.
 */
pwlib.guiTabPanel = function (gui, panel) {
  var _self    = this,
      appEvent = pwlib.appEvent,
      doc      = gui.app.doc,
      lang     = gui.app.lang;

  /**
   * Custom application events interface.
   * @type pwlib.appEvents
   */
  this.events = null;

  /**
   * Panel ID. The ID is the same as the data-pwTabPanel attribute value of the 
   * panel DOM element .
   *
   * @type String.
   */
  this.id = null;

  /**
   * Holds references to the DOM element of each tab and tab button.
   * @type Object
   */
  this.tabs = {};

  /**
   * Reference to the tab buttons DOM element.
   * @type Element
   */
  this.tabButtons = null;

  /**
   * The panel container DOM element.
   * @type Element
   */
  this.container = panel;

  /**
   * Holds the ID of the currently active tab.
   * @type String
   */
  this.tabId = null;

  /**
   * Holds the ID of the previously active tab.
   *
   * @private
   * @type String
   */
  var prevTabId_ = null;

  /**
   * Initialize the toolbar functionality.
   * @private
   */
  function init () {
    _self.events = new pwlib.appEvents(_self);
    _self.id = _self.container.getAttribute('data-pwTabPanel');

    // Add two class names, the generic .paintweb_tabPanel and another class 
    // name specific to the current tab panel: .paintweb_tabPanel_id. 
    _self.container.className += ' ' + gui.classPrefix + 'tabPanel' 
      + ' ' + gui.classPrefix + 'tabPanel_' + _self.id;

    var tabButtons = doc.createElement('ul'),
        tabButton = null,
        tabDefault = _self.container.getAttribute('data-pwTabDefault') || null,
        childNodes = _self.container.childNodes,
        type = gui.app.ELEMENT_NODE,
        elem = null,
        tabId = null,
        anchor = null;

    tabButtons.className = gui.classPrefix + 'tabsList';

    // Find all the tabs in the current panel container element.
    for (var i = 0; elem = childNodes[i]; i++) {
      if (elem.nodeType !== type) {
        continue;
      }

      // A tab is any element with a given data-pwTab attribute.
      tabId = elem.getAttribute('data-pwTab');
      if (!tabId) {
        continue;
      }

      // two class names, the generic .paintweb_tab and the tab-specific class 
      // name .paintweb_tabPanelId_tabId.
      elem.className += ' ' + gui.classPrefix + 'tab ' + gui.classPrefix 
        + _self.id + '_' + tabId;

      tabButton = doc.createElement('li');
      tabButton._pwTab = tabId;

      anchor = doc.createElement('a');
      anchor.href = '#';
      anchor.addEventListener('click', ev_tabClick, false);

      if (_self.id in lang.tabs) {
        anchor.title = lang.tabs[_self.id][tabId + 'Title'] || 
          lang.tabs[_self.id][tabId];
        anchor.appendChild(doc.createTextNode(lang.tabs[_self.id][tabId]));
      }

      if ((tabDefault && tabId === tabDefault) ||
          (!tabDefault && !_self.tabId)) {
        _self.tabId = tabId;
        tabButton.className = gui.classPrefix + 'tabActive';
      } else {
        prevTabId_ = tabId;
        elem.style.display = 'none';
      }

      // automatically hide the tab
      if (elem.getAttribute('data-pwTabHide') === 'true') {
        tabButton.style.display = 'none';
      }

      _self.tabs[tabId] = {container: elem, button: tabButton};

      tabButton.appendChild(anchor);
      tabButtons.appendChild(tabButton);
    }

    _self.tabButtons = tabButtons;
    _self.container.appendChild(tabButtons);
  };

  /**
   * The <code>click</code> event handler for tab buttons. This function simply 
   * activates the tab the user clicked.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_tabClick (ev) {
    ev.preventDefault();
    _self.tabActivate(this.parentNode._pwTab);
  };

  /**
   * Activate a tab by ID.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.guiTabActivate} event.
   *
   * @param {String} tabId The ID of the tab you want to activate.
   * @returns {Boolean} True if the tab has been activated successfully, or 
   * false if not.
   */
  this.tabActivate = function (tabId) {
    if (!tabId || !(tabId in this.tabs)) {
      return false;
    } else if (tabId === this.tabId) {
      return true;
    }

    var ev = new appEvent.guiTabActivate(tabId, this.tabId),
        cancel = this.events.dispatch(ev),
        elem = null,
        tabButton = null;

    if (cancel) {
      return false;
    }

    // Deactivate the currently active tab.
    if (this.tabId in this.tabs) {
      elem = this.tabs[this.tabId].container;
      elem.style.display = 'none';
      tabButton = this.tabs[this.tabId].button;
      tabButton.className = '';
      prevTabId_ = this.tabId;
    }

    // Activate the new tab.
    elem = this.tabs[tabId].container;
    elem.style.display = '';
    tabButton = this.tabs[tabId].button;
    tabButton.className = gui.classPrefix + 'tabActive';
    tabButton.style.display = ''; // make sure the tab is not hidden
    this.tabId = tabId;

    try {
      tabButton.firstChild.focus();
    } catch (err) { }

    return true;
  };

  /**
   * Hide a tab by ID.
   *
   * @param {String} tabId The ID of the tab you want to hide.
   * @returns {Boolean} True if the tab has been hidden successfully, or false 
   * if not.
   */
  this.tabHide = function (tabId) {
    if (!(tabId in this.tabs)) {
      return false;
    }

    if (this.tabId === tabId) {
      this.tabActivate(prevTabId_);
    }

    this.tabs[tabId].button.style.display = 'none';

    return true;
  };

  /**
   * Show a tab by ID.
   *
   * @param {String} tabId The ID of the tab you want to show.
   * @returns {Boolean} True if the tab has been displayed successfully, or 
   * false if not.
   */
  this.tabShow = function (tabId) {
    if (!(tabId in this.tabs)) {
      return false;
    }

    this.tabs[tabId].button.style.display = '';

    return true;
  };

  init();
};

/**
 * @class The GUI tab activation event. This event is cancelable.
 *
 * @augments pwlib.appEvent
 *
 * @param {String} tabId The ID of the tab being activated.
 * @param {String} prevTabId The ID of the previously active tab.
 */
pwlib.appEvent.guiTabActivate = function (tabId, prevTabId) {
  /**
   * The ID of the tab being activated.
   * @type String
   */
  this.tabId = tabId;

  /**
   * The ID of the previously active tab.
   * @type String
   */
  this.prevTabId = prevTabId;

  pwlib.appEvent.call(this, 'guiTabActivate', true);
};

/**
 * @class The color input GUI component.
 *
 * @private
 *
 * @param {pwlib.gui} gui Reference to the PaintWeb GUI object.
 *
 * @param {Element} input Reference to the DOM input element. This can be 
 * a span, a div, or any other tag.
 */
pwlib.guiColorInput = function (gui, input) {
  var _self      = this,
      colormixer = null,
      config     = gui.app.config,
      doc        = gui.app.doc,
      MathRound  = Math.round,
      lang       = gui.app.lang;

  /**
   * Color input ID. The ID is the same as the data-pwColorInput attribute value 
   * of the DOM input element .
   *
   * @type String.
   */
  this.id = null;

  /**
   * The color input element DOM reference.
   *
   * @type Element
   */
  this.input = input;

  /**
   * The configuration property to which this color input is attached to.
   * @type String
   */
  this.configProperty = null;

  /**
   * The configuration group to which this color input is attached to.
   * @type String
   */
  this.configGroup = null;

  /**
   * Reference to the configuration object which holds the color input value.
   * @type String
   */
  this.configGroupRef = null;

  /**
   * Holds the current color displayed by the input.
   *
   * @type Object
   */
  this.color = {red: 0, green: 0, blue: 0, alpha: 0};

  /**
   * Initialize the color input functionality.
   * @private
   */
  function init () {
    var cfgAttr     = _self.input.getAttribute('data-pwColorInput'),
        cfgNoDots   = cfgAttr.replace('.', '_'),
        cfgArray    = cfgAttr.split('.'),
        cfgProp     = cfgArray.pop(),
        cfgGroup    = cfgArray.join('.'),
        cfgGroupRef = config,
        langGroup   = lang.inputs,
        labelElem   = _self.input.parentNode,
        anchor      = doc.createElement('a'),
        color;

    for (var i = 0, n = cfgArray.length; i < n; i++) {
      cfgGroupRef = cfgGroupRef[cfgArray[i]];
      langGroup = langGroup[cfgArray[i]];
    }

    _self.configProperty = cfgProp;
    _self.configGroup = cfgGroup;
    _self.configGroupRef = cfgGroupRef;

    _self.id = cfgNoDots;

    _self.input.className += ' ' + gui.classPrefix + 'colorInput' 
      + ' ' + gui.classPrefix + _self.id;

    labelElem.replaceChild(doc.createTextNode(langGroup[cfgProp]), 
        labelElem.firstChild);

    color = _self.configGroupRef[_self.configProperty];
    color = color.replace(/\s+/g, '').replace(/^rgba\(/, '').replace(/\)$/, '');
    color = color.split(',');
    _self.color.red   = color[0] / 255;
    _self.color.green = color[1] / 255;
    _self.color.blue  = color[2] / 255;
    _self.color.alpha = color[3];

    anchor.style.backgroundColor = 'rgb(' + color[0] + ',' + color[1] + ',' 
        + color[2] + ')';
    anchor.style.opacity = color[3];

    anchor.href = '#';
    anchor.title = langGroup[cfgProp + 'Title'] || langGroup[cfgProp];
    anchor.appendChild(doc.createTextNode(lang.inputs.colorInputAnchorContent));
    anchor.addEventListener('click', ev_input_click, false);

    _self.input.replaceChild(anchor, _self.input.firstChild);
  };

  /**
   * The <code>click</code> event handler for the color input element. This 
   * function shows/hides the Color Mixer panel.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   */
  function ev_input_click (ev) {
    ev.preventDefault();

    if (!colormixer) {
      colormixer = gui.app.extensions.colormixer;
    }

    if (!colormixer.targetInput || colormixer.targetInput.id !== _self.id) {
      colormixer.show({
          id: _self.id,
          configProperty: _self.configProperty,
          configGroup: _self.configGroup,
          configGroupRef: _self.configGroupRef,
          show: colormixer_show,
          hide: colormixer_hide
        }, _self.color);

    } else {
      colormixer.hide();
    }
  };

  /**
   * The color mixer <code>show</code> event handler. This function is invoked 
   * when the color mixer is shown.
   * @private
   */
  function colormixer_show () {
    var classActive = ' ' + gui.classPrefix + 'colorInputActive',
        elemActive = _self.input.className.indexOf(classActive) !== -1;

    if (!elemActive) {
      _self.input.className += classActive;
    }
  };

  /**
   * The color mixer <code>hide</code> event handler. This function is invoked 
   * when the color mixer is hidden.
   * @private
   */
  function colormixer_hide () {
    var classActive = ' ' + gui.classPrefix + 'colorInputActive',
        elemActive = _self.input.className.indexOf(classActive) !== -1;

    if (elemActive) {
      _self.input.className = _self.input.className.replace(classActive, '');
    }
  };

  /**
   * Update color. This method allows the change of the color values associated 
   * to the current color input.
   *
   * <p>This method is used by the color picker tool and by the global GUI 
   * <code>configChange</code> application event handler.
   *
   * @param {Object} color The new color values. The object must have four 
   * properties: <var>red</var>, <var>green</var>, <var>blue</var> and 
   * <var>alpha</var>. All values must be between 0 and 1.
   */
  this.updateColor = function (color) {
    var anchor = _self.input.firstChild.style;

    anchor.opacity         = color.alpha;
    anchor.backgroundColor = 'rgb(' + MathRound(color.red   * 255) + ',' +
                                      MathRound(color.green * 255) + ',' +
                                      MathRound(color.blue  * 255) + ')';
    _self.color.red   = color.red;
    _self.color.green = color.green;
    _self.color.blue  = color.blue;
    _self.color.alpha = color.alpha;
  };

  init();
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

pwlib.fileCache['interfaces/default/layout.xhtml'] = 
"<div xmlns=\"http:\/\/www.w3.org\/1999\/xhtml\">  <h1 class=\"paintweb_appTitle\">PaintWeb<\/h1>  <div data-pwTabPanel=\"main\" data-pwTabDefault=\"main\">  <div data-pwTab=\"main\">  <ul id=\"tools\">  <li data-pwCommand=\"historyUndo\">Undo<\/li>  <li data-pwCommand=\"historyRedo\">Redo<\/li>  <li class=\"paintweb_toolSeparator\">&#160;<\/li>  <li data-pwCommand=\"imageClear\">Clear image<\/li>  <!--<li data-pwCommand=\"imageSave\">Save image<\/li>-->  <li data-pwTool=\"insertimg\">Insert image<\/li>  <li class=\"paintweb_toolSeparator\">&#160;<\/li>  <li data-pwCommand=\"selectionCut\">Cut selection<\/li>  <li data-pwCommand=\"selectionCopy\">Copy selection<\/li>  <li data-pwCommand=\"clipboardPaste\">Clipboard paste<\/li>  <li class=\"paintweb_toolSeparator\">&#160;<\/li>  <li data-pwTool=\"cpicker\">Color picker<\/li>  <li data-pwTool=\"cbucket\">Color bucket<\/li>  <li class=\"paintweb_toolsWrap\">&#160;<\/li>  <li data-pwTool=\"selection\">Selection<\/li>  <li data-pwTool=\"hand\">Hand<\/li>  <li class=\"paintweb_toolSeparator\">&#160;<\/li>  <li data-pwTool=\"rectangle\">Rectangle<\/li>  <li data-pwTool=\"ellipse\">Ellipse<\/li>  <li data-pwTool=\"polygon\">Polygon<\/li>  <li data-pwTool=\"line\">Line<\/li>  <li data-pwTool=\"bcurve\">B\u00e9zier curve<\/li>  <li data-pwTool=\"text\">Text<\/li>  <li data-pwTool=\"pencil\">Pencil<\/li>  <li class=\"paintweb_toolSeparator\">&#160;<\/li>  <li data-pwTool=\"eraser\">Eraser<\/li>  <li class=\"paintweb_toolSeparator\">&#160;<\/li>  <\/ul>  <div class=\"paintweb_strokeFillStyles\">  <p class=\"paintweb_opt_fillStyle\">Fill <span   data-pwColorInput=\"fillStyle\">&#160;<\/span>  <\/p>  <p class=\"paintweb_opt_strokeStyle\">Stroke <span   data-pwColorInput=\"strokeStyle\">&#160;<\/span>  <\/p>  <\/div>  <div class=\"paintweb_strokeFillStyles search-button-img\">  <span data-pwCommand=\"imageSave\"><\/span>  <\/div>  <\/div>  <div data-pwTab=\"line\" data-pwTabHide=\"true\">  <p class=\"paintweb_opt_lineWidth\"><label>Line width <input   data-pwConfig=\"line.lineWidth\" type=\"number\" min=\"1\" value=\"1\"   \/><\/label><\/p>  <p class=\"paintweb_opt_miterLimit\"><label>Miter limit <input    data-pwConfig=\"line.miterLimit\" type=\"number\" min=\"1\" value=\"10\"   \/><\/label><\/p>  <div data-pwConfig=\"line.lineCap\">  <p>Line cap<\/p>  <div data-pwConfigValue=\"butt\">Butt<\/div>  <div data-pwConfigValue=\"square\">Square<\/div>  <div data-pwConfigValue=\"round\">Round<\/div>  <\/div>  <div data-pwConfig=\"line.lineJoin\">  <p>Line join<\/p>  <div data-pwConfigValue=\"miter\">Miter<\/div>  <div data-pwConfigValue=\"round\">Round<\/div>  <div data-pwConfigValue=\"bevel\">Bevel<\/div>  <\/div>  <div data-pwConfig=\"shapeType\">  <p>Shape type<\/p>  <div data-pwConfigValue=\"both\">Both<\/div>  <div data-pwConfigValue=\"fill\">Fill<\/div>  <div data-pwConfigValue=\"stroke\">Stroke<\/div>  <\/div>  <\/div>  <div data-pwTab=\"selection\" data-pwTabHide=\"true\">  <p data-pwId=\"selTab_selectionCut\">Cut selection<\/p>  <p data-pwId=\"selTab_selectionCopy\">Copy selection<\/p>  <p data-pwId=\"selTab_clipboardPaste\">Clipboard paste<\/p>  <p data-pwCommand=\"selectionCrop\">Crop selection<\/p>  <p data-pwCommand=\"selectionDelete\">Delete selection<\/p>  <p data-pwCommand=\"selectionFill\">Fill selection<\/p>  <p class=\"paintweb_opt_selectionTransparent\">  <label><input data-pwConfig=\"selection.transparent\" type=\"checkbox\"   value=\"1\" checked=\"checked\" \/> Transparent background<\/label>  <\/p>  <p class=\"paintweb_opt_selectionTransform\">  <label><input data-pwConfig=\"selection.transform\" type=\"checkbox\"   value=\"1\" \/> Transformation mode<\/label>  <\/p>  <\/div>  <div data-pwTab=\"text\" data-pwTabHide=\"true\">  <p class=\"paintweb_opt_fontFamily\">  <label for=\"fontFamily\">Font family:<\/label>  <select id=\"fontFamily\" data-pwConfig=\"text.fontFamily\"><\/select>  <\/p>  <p class=\"paintweb_opt_fontSize\">  <label for=\"fontSize\">Font size:<\/label>  <input id=\"fontSize\" data-pwConfig=\"text.fontSize\" type=\"number\" min=\"6\"   value=\"12\" \/>  <\/p>  <div data-pwConfigToggle=\"text.bold\">Bold<\/div>  <div data-pwConfigToggle=\"text.italic\">Italic<\/div>  <div data-pwConfig=\"text.textAlign\">  <p>Text alignment<\/p>  <div data-pwConfigValue=\"left\">Left<\/div>  <div data-pwConfigValue=\"center\">Center<\/div>  <div data-pwConfigValue=\"right\">Right<\/div>  <\/div>  <p class=\"paintweb_opt_textString\">  <label>String <textarea id=\"textString\" rows=\"2\" cols=\"4\">Hello   world!<\/textarea><\/label>  <\/p>  <\/div>  <div data-pwTab=\"shadow\">  <p class=\"paintweb_opt_shadowEnable\"><label><input   data-pwConfig=\"shadow.enable\" type=\"checkbox\" value=\"1\" \/> Draw   shadows<\/label><\/p>  <p class=\"paintweb_opt_shadowColor\">Color <span   data-pwColorInput=\"shadow.shadowColor\">&#160;<\/span>  <\/p>  <p class=\"paintweb_opt_shadowOffsetX\">  <label>Offset X  <input data-pwConfig=\"shadow.shadowOffsetX\" type=\"number\" value=\"5\" \/>  <\/label>  <\/p>  <p class=\"paintweb_opt_shadowOffsetY\">  <label>Offset Y  <input data-pwConfig=\"shadow.shadowOffsetY\" type=\"number\" value=\"5\" \/>  <\/label>  <\/p>  <p class=\"paintweb_opt_shadowBlur\">  <label>Blur  <input data-pwConfig=\"shadow.shadowBlur\" type=\"number\" value=\"5\"   min=\"0\" \/>  <\/label>  <\/p>  <\/div>  <p data-pwCommand=\"about\">About<\/p>  <\/div>   <div id=\"viewport\">  <div id=\"canvasContainer\">  <\/div>  <div id=\"canvasResizer\">Resize the image Canvas.<\/div>  <\/div>   <div class=\"paintweb_statusbar\">  <p id=\"imageSize\">WxH<\/p>  <p id=\"statusZoom\" title=\"Zoom image\">  <label>Zoom: <input id=\"imageZoom\" type=\"number\" min=\"20\" max=\"400\"   value=\"100\" step=\"10\" \/><\/label>  <\/p>  <p id=\"statusMessage\">Status<\/p>  <p id=\"viewportResizer\">Resize the image viewport.<\/p>  <\/div>   <div data-pwFloatingPanel=\"colormixer\" data-pwPanelHide=\"true\">  <h1>Color mixer<\/h1>  <div>  <ol class=\"paintweb_colormixer_preview\">  <li id=\"colormixer_colorActive\"><span>&#160;<\/span> Active<\/li>  <li id=\"colormixer_colorOld\"><span>&#160;<\/span> Old<\/li>  <\/ol>  <ol class=\"paintweb_colormixer_actions\">  <li id=\"colormixer_btn_accept\">Close<\/li>  <li id=\"colormixer_btn_cancel\">Cancel<\/li>  <li id=\"colormixer_btn_saveColor\">Save color<\/li>  <li id=\"colormixer_btn_pickColor\">Pick color<\/li>  <\/ol>  <div data-pwTabPanel=\"colormixer_selector\" data-pwTabDefault=\"mixer\">  <div data-pwTab=\"mixer\">  <canvas id=\"colormixer_canvas\" width=\"200\" height=\"195\">Your browser   does not support Canvas.<\/canvas>  <div id=\"colormixer_controls\">  <span id=\"colormixer_chartDot\"><\/span>  <span id=\"colormixer_slider\"><\/span>  <\/div>  <\/div>  <div data-pwTab=\"cpalettes\">  <select id=\"colormixer_cpaletteInput\"><\/select>  <div id=\"colormixer_cpaletteOutput\"><\/div>  <\/div>  <\/div>  <ol class=\"paintweb_colormixer_hexalpha\">  <li><label>HEX  <input id=\"ckey_hex\" value=\"#RRGGBB\" type=\"text\" maxlength=\"7\"   pattern=\"#[a-f0-9]{6}\" \/><\/label>  <\/li>  <li><label>Alpha  <input id=\"ckey_alpha\" value=\"100\" type=\"number\" min=\"0\" max=\"100\"   step=\"1\" \/><\/label>  <\/li>  <\/ol>  <form data-pwTabPanel=\"colormixer_inputs\" data-pwTabDefault=\"rgb\">  <ol data-pwTab=\"rgb\">  <li>  <input name=\"ckey\" value=\"red\" type=\"radio\" \/>  <label>Red  <input name=\"ckey_red\" value=\"0\" type=\"number\" min=\"0\" max=\"255\"   step=\"1\" \/><\/label>  <\/li>  <li>  <input name=\"ckey\" value=\"green\" type=\"radio\" \/>  <label>Green  <input name=\"ckey_green\" value=\"0\" type=\"number\" min=\"0\" max=\"255\"   step=\"1\" \/><\/label>  <\/li>  <li>  <input name=\"ckey\" value=\"blue\" type=\"radio\" \/>  <label>Blue  <input name=\"ckey_blue\" value=\"0\" type=\"number\" min=\"0\" max=\"255\"   step=\"1\" \/><\/label>  <\/li>  <\/ol>  <ol data-pwTab=\"hsv\">  <li>  <input name=\"ckey\" value=\"hue\" type=\"radio\" \/>  <label>Hue  <input name=\"ckey_hue\" value=\"0\" type=\"number\" min=\"0\" max=\"360\"   step=\"1\" \/><\/label>  <\/li>  <li>  <input name=\"ckey\" value=\"sat\" type=\"radio\" \/>  <label>Saturation  <input name=\"ckey_sat\" value=\"0\" type=\"number\" min=\"0\" max=\"255\"   step=\"1\" \/><\/label>  <\/li>  <li>  <input name=\"ckey\" value=\"val\" type=\"radio\" \/>  <label>Value  <input name=\"ckey_val\" value=\"0\" type=\"number\" min=\"0\" max=\"255\"   step=\"1\" \/><\/label>  <\/li>  <\/ol>  <ol data-pwTab=\"lab\">  <li>  <input name=\"ckey\" value=\"cie_l\" type=\"radio\" \/>  <label>Lightness  <input name=\"ckey_cie_l\" value=\"0\" type=\"number\" min=\"0\"      max=\"100\" step=\"1\" \/><\/label>  <\/li>  <li>  <input name=\"ckey\" value=\"cie_a\" type=\"radio\" \/>  <label>a*  <input name=\"ckey_cie_a\" value=\"0\" type=\"number\" min=\"-85\"    max=\"94\" step=\"1\" \/><\/label>  <\/li>  <li>  <input name=\"ckey\" value=\"cie_b\" type=\"radio\" \/>  <label>b*  <input name=\"ckey_cie_b\" value=\"0\" type=\"number\" min=\"-109\"   max=\"95\" step=\"1\" \/><\/label>  <\/li>  <\/ol>  <ol data-pwTab=\"cmyk\">  <li>  <label>Cyan  <input name=\"ckey_cyan\" value=\"0\" type=\"number\" min=\"0\" max=\"100\"   step=\"1\" \/><\/label>  <\/li>  <li>  <label>Magenta  <input name=\"ckey_magenta\" value=\"0\" type=\"number\" min=\"0\"   max=\"100\" step=\"1\" \/><\/label>  <\/li>  <li>  <label>Yellow  <input name=\"ckey_yellow\" value=\"0\" type=\"number\" min=\"0\" max=\"100\"   step=\"1\" \/><\/label>  <\/li>  <li>  <label>Key (Black)  <input name=\"ckey_black\" value=\"0\" type=\"number\" min=\"0\" max=\"100\"   step=\"1\" \/><\/label>  <\/li>  <\/ol>  <\/form>   <\/div>   <\/div>   <div data-pwFloatingPanel=\"about\" data-pwPanelHide=\"true\">  <h1>About<\/h1>  <div>  <ul>  <li id=\"version\"><strong>Version:<\/strong> <\/li>  <li><strong>Authors:<\/strong> <a href=\"http:\/\/www.robodesign.ro\">Marius   and Mihai \u015eucan (ROBO Design)<\/a><\/li>  <li><strong>Project site:<\/strong> <a   href=\"http:\/\/code.google.com\/p\/paintweb\">code.google.com\/p\/paintweb<\/a><\/li>  <li><strong>Code license:<\/strong> <a   href=\"http:\/\/www.gnu.org\/licenses\/gpl-3.0.html\" title=\"GNU General   Public License, version 3\">GPLv3<\/a><\/li>  <\/ul>  <p>For user and developer documentation please check out the <a   href=\"http:\/\/code.google.com\/p\/paintweb\">project site<\/a>.<\/p>  <\/div>  <\/div>  <\/div>";
/*
 * Copyright (C) 2008, 2009, 2010 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2010-06-26 22:44:23 +0300 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview The main PaintWeb application code.
 */

/**
 * @class The PaintWeb application object.
 *
 * @param {Window} [win=window] The window object to use.
 * @param {Document} [doc=document] The document object to use.
 */
function PaintWeb (win, doc) {
  var _self = this;

  if (!win) {
    win = window;
  }
  if (!doc) {
    doc = document;
  }

  /**
   * PaintWeb version.
   * @type Number
   */
  this.version = 0.9; //!

  /**
   * PaintWeb build date (YYYYMMDD).
   * @type Number
   */
  this.build = 20111103;

  /**
   * Holds all the PaintWeb configuration.
   * @type Object
   */
  this.config = {
    showErrors: true
  };

  /**
   * Holds all language strings used within PaintWeb.
   */
  // Here we include a minimal set of strings, used in case the language file will 
  // not load.
  this.lang = {
    "noComputedStyle": "Error: window.getComputedStyle is not available.",
    "noXMLHttpRequest": "Error: window.XMLHttpRequest is not available.",
    "noCanvasSupport": "Error: Your browser does not support Canvas.",
    "guiPlaceholderWrong": "Error: The config.guiPlaceholder property must " +
      "reference a DOM element!",
    "initHandlerMustBeFunction": "The first argument must be a function.",
    "noConfigFile": "Error: You must point to a configuration file by " +
      "setting the config.configFile property!",
    "failedConfigLoad": "Error: Failed loading the configuration file.",
    "failedLangLoad": "Error: Failed loading the language file."
  };

  /**
   * Holds the buffer canvas and context references.
   * @type Object
   */
  this.buffer = {canvas: null, context: null};

  /**
   * Holds the current layer ID, canvas and context references.
   * @type Object
   */
  this.layer = {id: null, canvas: null, context: null};

  /**
   * The instance of the active tool object.
   *
   * @type Object
   *
   * @see PaintWeb.config.toolDefault holds the ID of the tool which is 
   * activated when the application loads.
   * @see PaintWeb#toolActivate Activate a drawing tool by ID.
   * @see PaintWeb#toolRegister Register a new drawing tool.
   * @see PaintWeb#toolUnregister Unregister a drawing tool.
   * @see pwlib.tools holds the drawing tools.
   */
  this.tool = null;

  /**
   * Holds references to DOM elements.
   *
   * @private
   * @type Object
   */
  this.elems = {};

  /**
   * Holds the last recorded mouse coordinates and the button state (if it's 
   * down or not).
   *
   * @private
   * @type Object
   */
  this.mouse = {x: 0, y: 0, buttonDown: false};

  /**
   * Holds all the PaintWeb extensions.
   *
   * @type Object
   * @see PaintWeb#extensionRegister Register a new extension.
   * @see PaintWeb#extensionUnregister Unregister an extension.
   * @see PaintWeb.config.extensions Holds the list of extensions to be loaded 
   * automatically when PaintWeb is initialized.
   */
  this.extensions = {};

  /**
   * Holds all the PaintWeb commands. Each property in this object must 
   * reference a simple function which can be executed by keyboard shortcuts 
   * and/or GUI elements.
   *
   * @type Object
   * @see PaintWeb#commandRegister Register a new command.
   * @see PaintWeb#commandUnregister Unregister a command.
   */
  this.commands = {};

  /**
   * The graphical user interface object instance.
   * @type pwlib.gui
   */
  this.gui = null;

  /**
   * The document element PaintWeb is working with.
   *
   * @private
   * @type Document
   * @default document
   */
  this.doc = doc;

  /**
   * The window object PaintWeb is working with.
   *
   * @private
   * @type Window
   * @default window
   */
  this.win = win;

  /**
   * Holds image information: width, height, zoom and more.
   *
   * @type Object
   */
  this.image = {
    /**
     * Image width.
     *
     * @type Number
     */
    width: 0,

    /**
     * Image height.
     *
     * @type Number
     */
    height: 0,

    /**
     * Image zoom level. This property holds the current image zoom level used 
     * by the user for viewing the image.
     *
     * @type Number
     * @default 1
     */
    zoom: 1,

    /**
     * Image scaling. The canvas elements are scaled from CSS using this value 
     * as the scaling factor. This value is dependant on the browser rendering 
     * resolution and on the user-defined image zoom level.
     *
     * @type Number
     * @default 1
     */
    canvasScale: 1,

    /**
     * Tells if the current image has been modified since the initial load.
     *
     * @type Boolean
     * @default false
     */
    modified: false
  };

  /**
   * Resolution information.
   *
   * @type Object
   */
  this.resolution = {
    /**
     * The DOM element holding information about the current browser rendering 
     * settings (zoom / DPI).
     *
     * @private
     * @type Element
     */
    elem: null,

    /**
     * The ID of the DOM element holding information about the current browser 
     * rendering settings (zoom / DPI).
     *
     * @private
     * @type String
     * @default 'paintweb_resInfo'
     */
    elemId: 'paintweb_resInfo',

    /**
     * The styling necessary for the DOM element.
     *
     * @private
     * @type String
     */
    cssText: '@media screen and (resolution:96dpi){' +
             '#paintweb_resInfo{width:96px}}' +
             '@media screen and (resolution:134dpi){' +
             '#paintweb_resInfo{width:134px}}' +
             '@media screen and (resolution:200dpi){' +
             '#paintweb_resInfo{width:200px}}' +
             '@media screen and (resolution:300dpi){' +
             '#paintweb_resInfo{width:300px}}' +
             '#paintweb_resInfo{' +
             'display:block;' +
             'height:100%;' +
             'left:-3000px;' +
             'position:fixed;' +
             'top:0;' +
             'visibility:hidden;' +
             'z-index:-32}',

    /**
     * Optimal DPI for the canvas elements.
     *
     * @private
     * @type Number
     * @default 96
     */
    dpiOptimal: 96,

    /**
     * The current DPI used by the browser for rendering the entire page.
     *
     * @type Number
     * @default 96
     */
    dpiLocal: 96,

    /**
     * The current zoom level used by the browser for rendering the entire page.
     *
     * @type Number
     * @default 1
     */
    browserZoom: 1,

    /**
     * The scaling factor used by the browser for rendering the entire page. For 
     * example, on Gecko using DPI 200 the scale factor is 2.
     *
     * @private
     * @type Number
     * @default -1
     */
    scale: -1
  };

  /**
   * The image history.
   *
   * @private
   * @type Object
   */
  this.history = {
    /**
     * History position.
     *
     * @type Number
     * @default 0
     */
    pos: 0,

    /**
     * The ImageDatas for each history state.
     *
     * @private
     * @type Array
     */
    states: []
  };

  /**
   * Tells if the browser supports the Canvas Shadows API.
   *
   * @type Boolean
   * @default true
   */
  this.shadowSupported = true;

  /**
   * Tells if the current tool allows the drawing of shadows.
   *
   * @type Boolean
   * @default true
   */
  this.shadowAllowed = true;

  /**
   * Image in the clipboard. This is used when some selection is copy/pasted.  
   * 
   * @type ImageData
   */
  this.clipboard = false;

  /**
   * Application initialization state. This property can be in one of the 
   * following states:
   *
   * <ul>
   *   <li>{@link PaintWeb.INIT_NOT_STARTED} - The initialization is not 
   *   started.
   *
   *   <li>{@link PaintWeb.INIT_STARTED} - The initialization process is 
   *   running.
   *
   *   <li>{@link PaintWeb.INIT_DONE} - The initialization process has completed 
   *   successfully.
   *
   *   <li>{@link PaintWeb.INIT_ERROR} - The initialization process has failed.
   * </ul>
   *
   * @type Number
   * @default PaintWeb.INIT_NOT_STARTED
   */
  this.initialized = PaintWeb.INIT_NOT_STARTED;

  /**
   * Custom application events object.
   *
   * @type pwlib.appEvents
   */
  this.events = null;

  /**
   * Unique ID for the current PaintWeb instance.
   *
   * @type Number
   */
  this.UID = 0;

  /**
   * List of Canvas context properties to save and restore.
   *
   * <p>When the Canvas is resized the state is lost. Using context.save/restore 
   * state does work only in Opera. In Firefox/Gecko and WebKit saved states are 
   * lost after resize, so there's no state to restore. As such, PaintWeb has 
   * its own simple state save/restore mechanism. The property values are saved 
   * into a JavaScript object.
   *
   * @private
   * @type Array
   *
   * @see PaintWeb#stateSave to save the canvas context state.
   * @see PaintWeb#stateRestore to restore a canvas context state.
   */
  this.stateProperties = ['strokeStyle', 'fillStyle', 'globalAlpha', 
    'lineWidth', 'lineCap', 'lineJoin', 'miterLimit', 'shadowOffsetX', 
    'shadowOffsetY', 'shadowBlur', 'shadowColor', 'globalCompositeOperation', 
    'font', 'textAlign', 'textBaseline'];

  /**
   * Holds the keyboard event listener object.
   *
   * @private
   * @type pwlib.dom.KeyboardEventListener
   * @see pwlib.dom.KeyboardEventListener The class dealing with the 
   * cross-browser differences in the DOM keyboard events.
   */
  var kbListener_ = null;

  /**
   * Holds temporary state information during PaintWeb initialization.
   *
   * @private
   * @type Object
   */
  var temp_ = {onInit: null, toolsLoadQueue: 0, extensionsLoadQueue: 0};

  // Avoid global scope lookup.
  var MathAbs   = Math.abs,
      MathFloor = Math.floor,
      MathMax   = Math.max,
      MathMin   = Math.min,
      MathRound = Math.round,
      pwlib     = null,
      appEvent  = null,
      lang      = this.lang;

  /**
   * Element node type constant.
   *
   * @constant
   * @type Number
   */
  this.ELEMENT_NODE = window.Node ? Node.ELEMENT_NODE : 1;

  /**
   * PaintWeb pre-initialization code. This runs when the PaintWeb instance is 
   * constructed.
   * @private
   */
  function preInit() {
    var d = new Date();

    // If PaintWeb is running directly from the source code, then the build date 
    // is always today.
    if (_self.build === -1) {
      var dateArr = [d.getFullYear(), d.getMonth()+1, d.getDate()];

      if (dateArr[1] < 10) {
        dateArr[1] = '0' + dateArr[1];
      }
      if (dateArr[2] < 10) {
        dateArr[2] = '0' + dateArr[2];
      }

      _self.build = dateArr.join('');
    }

    _self.UID = d.getMilliseconds() * MathRound(Math.random() * 100);
    _self.elems.head = doc.getElementsByTagName('head')[0] || doc.body;
  };

  /**
   * Initialize PaintWeb.
   *
   * <p>This method is asynchronous, meaning that it will return much sooner 
   * before the application initialization is completed.
   *
   * @param {Function} [handler] The <code>appInit</code> event handler. Your 
   * event handler will be invoked automatically when PaintWeb completes 
   * loading, or when an error occurs.
   *
   * @returns {Boolean} True if the initialization has been started 
   * successfully, or false if not.
   */
  this.init = function (handler) {
    if (this.initialized === PaintWeb.INIT_DONE) {
      return true;
    }

    this.initialized = PaintWeb.INIT_STARTED;

    if (handler && typeof handler !== 'function') {
      throw new TypeError(lang.initHandlerMustBeFunction);
    }

    temp_.onInit = handler;

    // Check Canvas support.
    if (!doc.createElement('canvas').getContext) {
      this.initError(lang.noCanvasSupport);
      return false;
    }

    // Basic functionality used within the Web application.
    if (!window.getComputedStyle) {
      try {
        if (!win.getComputedStyle(doc.createElement('div'), null)) {
          this.initError(lang.noComputedStyle);
          return false;
        }
      } catch (err) {
        this.initError(lang.noComputedStyle);
        return false;
      }
    }

    if (!window.XMLHttpRequest) {
      this.initError(lang.noXMLHttpRequest);
      return false;
    }

    if (!this.config.configFile) {
      this.initError(lang.noConfigFile);
      return false;
    }

    if (typeof this.config.guiPlaceholder !== 'object' || 
        this.config.guiPlaceholder.nodeType !== this.ELEMENT_NODE) {
      this.initError(lang.guiPlaceholderWrong);
      return false;
    }

    // Silently ignore any wrong value for the config.imageLoad property.
    if (typeof this.config.imageLoad !== 'object' || 
        this.config.imageLoad.nodeType !== this.ELEMENT_NODE) {
      this.config.imageLoad = null;
    }

    // JSON parser and serializer.
    if (!window.JSON) {
      this.scriptLoad(PaintWeb.baseFolder + 'includes/json2.js', 
          this.jsonlibReady);
    } else {
      this.jsonlibReady();
    }

    return true;
  };

  /**
   * The <code>load</code> event handler for the JSON library script.
   * @private
   */
  this.jsonlibReady = function () {
    if (window.pwlib) {
      _self.pwlibReady();
    } else {
      _self.scriptLoad(PaintWeb.baseFolder + 'includes/lib.js', 
          _self.pwlibReady);
    }
  };

  /**
   * The <code>load</code> event handler for the PaintWeb library script.
   * @private
   */
  this.pwlibReady = function () {
    pwlib = window.pwlib;
    appEvent = pwlib.appEvent;

    // Create the custom application events object.
    _self.events = new pwlib.appEvents(_self);
    _self.configLoad();
  };

  /**
   * Report an initialization error.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.appInit} event.
   *
   * @private
   *
   * @param {String} msg The error message.
   *
   * @see pwlib.appEvent.appInit
   */
  this.initError = function (msg) {
    switch (this.initialized) {
      case PaintWeb.INIT_ERROR:
      case PaintWeb.INIT_DONE:
      case PaintWeb.INIT_NOT_STARTED:
        return;
    }

    this.initialized = PaintWeb.INIT_ERROR;

    var ev = null;

    if (this.events && 'dispatch' in this.events &&
        appEvent    && 'appInit'  in appEvent) {

      ev = new appEvent.appInit(this.initialized, msg);
      this.events.dispatch(ev);
    }

    if (typeof temp_.onInit === 'function') {
      if (!ev) {
        // fake an event dispatch.
        ev = {type: 'appInit', state: this.initialized, errorMessage: msg};
      }

      temp_.onInit.call(this, ev);
    }

    if (this.config.showErrors) {
      alert(msg);
    } else if (window.console && console.log) {
      console.log(msg);
    }
  };

  /**
   * Asynchronously load the configuration file. This method issues an 
   * XMLHttpRequest to load the JSON file.
   *
   * @private
   *
   * @see PaintWeb.config.configFile The configuration file.
   * @see pwlib.xhrLoad The library function being used for creating the 
   * XMLHttpRequest object.
   */
  this.configLoad = function () {
    pwlib.xhrLoad(PaintWeb.baseFolder + this.config.configFile, 
        this.configReady);
  };

  /**
   * The configuration reader. This is the event handler for the XMLHttpRequest 
   * object, for the <code>onreadystatechange</code> event.
   *
   * @private
   *
   * @param {XMLHttpRequest} xhr The XMLHttpRequest object being handled.
   *
   * @see PaintWeb#configLoad The method which issues the XMLHttpRequest request 
   * for loading the configuration file.
   */
  this.configReady = function (xhr) {
    /*
     * readyState values:
     *   0 UNINITIALIZED open() has not been called yet.
     *   1 LOADING send() has not been called yet.
     *   2 LOADED send() has been called, headers and status are available.
     *   3 INTERACTIVE Downloading, responseText holds the partial data.
     *   4 COMPLETED Finished with all operations.
     */
    if (!xhr || xhr.readyState !== 4) {
      return;
    }

    if ((xhr.status !== 304 && xhr.status !== 200) || !xhr.responseText) {
      _self.initError(lang.failedConfigLoad);
      return;
    }

    var config = pwlib.jsonParse(xhr.responseText);
    pwlib.extend(_self.config, config);

    _self.langLoad();
  };

  /**
   * Asynchronously load the language file. This method issues an XMLHttpRequest 
   * to load the JSON file.
   *
   * @private
   *
   * @see PaintWeb.config.lang The language you want for the PaintWeb user 
   * interface.
   * @see pwlib.xhrLoad The library function being used for creating the 
   * XMLHttpRequest object.
   */
  this.langLoad = function () {
    var id   = this.config.lang,
        file = PaintWeb.baseFolder;

    // If the language is not available, always fallback to English.
    if (!(id in this.config.languages)) {
      id = this.config.lang = 'en';
    }

    if ('file' in this.config.languages[id]) {
      file += this.config.languages[id].file;
    } else {
      file += this.config.langFolder + '/' + id + '.json';
    }

    pwlib.xhrLoad(file, this.langReady);
  };

  /**
   * The language file reader. This is the event handler for the XMLHttpRequest 
   * object, for the <code>onreadystatechange</code> event.
   *
   * @private
   *
   * @param {XMLHttpRequest} xhr The XMLHttpRequest object being handled.
   *
   * @see PaintWeb#langLoad The method which issues the XMLHttpRequest request 
   * for loading the language file.
   */
  this.langReady = function (xhr) {
    if (!xhr || xhr.readyState !== 4) {
      return;
    }

    if ((xhr.status !== 304 && xhr.status !== 200) || !xhr.responseText) {
      _self.initError(lang.failedLangLoad);
      return;
    }

    pwlib.extend(_self.lang, pwlib.jsonParse(xhr.responseText));

    if (_self.initCanvas() && _self.initContext()) {
      // Start GUI load now.
      _self.guiLoad();
    } else {
      _self.initError(lang.errorInitCanvas);
    }
  };

  /**
   * Initialize the PaintWeb commands.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initCommands = function () {
    if (this.commandRegister('historyUndo',    this.historyUndo) &&
        this.commandRegister('historyRedo',    this.historyRedo) &&
        this.commandRegister('selectAll',      this.selectAll) &&
        this.commandRegister('selectionCut',   this.selectionCut) &&
        this.commandRegister('selectionCopy',  this.selectionCopy) &&
        this.commandRegister('clipboardPaste', this.clipboardPaste) &&
        this.commandRegister('imageSave',      this.imageSave) &&
        this.commandRegister('imageClear',     this.imageClear) &&
        this.commandRegister('swapFillStroke', this.swapFillStroke) &&
        this.commandRegister('imageZoomIn',    this.imageZoomIn) &&
        this.commandRegister('imageZoomOut',   this.imageZoomOut) &&
        this.commandRegister('imageZoomReset', this.imageZoomReset)) {
      return true;
    } else {
      this.initError(lang.errorInitCommands);
      return false;
    }
  };

  /**
   * Load th PaintWeb GUI. This method loads the GUI markup file, the stylesheet 
   * and the script.
   *
   * @private
   *
   * @see PaintWeb.config.guiStyle The interface style file.
   * @see PaintWeb.config.guiScript The interface script file.
   * @see pwlib.gui The interface object.
   */
  this.guiLoad = function () {
    var cfg    = this.config,
        gui    = this.config.gui,
        base   = PaintWeb.baseFolder + cfg.interfacesFolder + '/' + gui + '/',
        style  = base + cfg.guiStyle,
        script = base + cfg.guiScript;

    this.styleLoad(gui + 'style', style);

    if (pwlib.gui) {
      this.guiScriptReady();
    } else {
      this.scriptLoad(script, this.guiScriptReady);
    }
  };

  /**
   * The <code>load</code> event handler for the PaintWeb GUI script. This 
   * method creates an instance of the GUI object that just loaded and starts 
   * loading the GUI markup.
   *
   * @private
   *
   * @see PaintWeb.config.guiScript The interface script file.
   * @see PaintWeb.config.guiMarkup The interface markup file.
   * @see pwlib.gui The interface object.
   * @see pwlib.xhrLoad The library function being used for creating the 
   * XMLHttpRequest object.
   */
  this.guiScriptReady = function () {
    var cfg    = _self.config,
        gui    = _self.config.gui,
        base   = cfg.interfacesFolder + '/' + gui + '/',
        markup = base + cfg.guiMarkup;

    _self.gui = new pwlib.gui(_self);

    // Check if the interface markup is cached already.
    if (markup in pwlib.fileCache) {
      if (_self.gui.init(pwlib.fileCache[markup])) {
        _self.initTools();
      } else {
        _self.initError(lang.errorInitGUI);
      }

    } else {
      pwlib.xhrLoad(PaintWeb.baseFolder + markup, _self.guiMarkupReady);
    }
  };

  /**
   * The GUI markup reader. This is the event handler for the XMLHttpRequest 
   * object, for the <code>onreadystatechange</code> event.
   *
   * @private
   *
   * @param {XMLHttpRequest} xhr The XMLHttpRequest object being handled.
   *
   * @see PaintWeb#guiScriptReady The method which issues the XMLHttpRequest 
   * request for loading the interface markup file.
   */
  this.guiMarkupReady = function (xhr) {
    if (!xhr || xhr.readyState !== 4) {
      return;
    }

    if (xhr.status !== 304 && xhr.status !== 200) {
      _self.initError(lang.failedMarkupLoad);
      return;
    }

    var param;
    if (xhr.responseXML && xhr.responseXML.documentElement) {
      param = xhr.responseXML;
    } else if (xhr.responseText) {
      param = xhr.responseText;
    } else {
      _self.initError(lang.failedMarkupLoad);
      return;
    }

    if (_self.gui.init(param)) {
      _self.initTools();
    } else {
      _self.initError(lang.errorInitGUI);
    }
  };

  /**
   * Initialize the Canvas elements. This method creates the elements and 
   * sets-up their dimensions.
   *
   * <p>The layer Canvas element will have the background rendered with the 
   * color from {@link PaintWeb.config.backgroundColor}.
   * 
   * <p>If {@link PaintWeb.config.imageLoad} is defined, then the image element 
   * is inserted into the Canvas image.
   *
   * <p>All the Canvas event listeners are also attached to the buffer Canvas 
   * element.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   *
   * @see PaintWeb#ev_canvas The global Canvas events handler.
   */
  this.initCanvas = function () {
    var cfg           = this.config,
        res           = this.resolution,
        resInfo       = doc.getElementById(res.elemId),
        layerCanvas   = doc.createElement('canvas'),
        bufferCanvas  = doc.createElement('canvas'),
        layerContext  = layerCanvas.getContext('2d'),
        bufferContext = bufferCanvas.getContext('2d'),
        width         = cfg.imageWidth,
        height        = cfg.imageHeight,
        imageLoad     = cfg.imageLoad;

    if (!resInfo) {
      var style = doc.createElement('style');
      style.type = 'text/css';
      style.appendChild(doc.createTextNode(res.cssText));
      _self.elems.head.appendChild(style);

      resInfo = doc.createElement('div');
      resInfo.id = res.elemId;
      doc.body.appendChild(resInfo);
    }

    if (!resInfo) {
      this.initError(lang.errorInitCanvas);
      return false;
    }
    if (!layerCanvas || !bufferCanvas || !layerContext || !bufferContext) {
      this.initError(lang.noCanvasSupport);
      return false;
    }

    if (!pwlib.isSameHost(imageLoad.src, win.location.host)) {
      cfg.imageLoad = imageLoad = null;
      alert(lang.imageLoadDifferentHost);
    }

    if (imageLoad) {
      width  = parseInt(imageLoad.width);
      height = parseInt(imageLoad.height);
    }

    res.elem = resInfo;

    this.image.width  = layerCanvas.width  = bufferCanvas.width  = width;
    this.image.height = layerCanvas.height = bufferCanvas.height = height;

    this.layer.canvas   = layerCanvas;
    this.layer.context  = layerContext;
    this.buffer.canvas  = bufferCanvas;
    this.buffer.context = bufferContext;

    if (imageLoad) {
      layerContext.drawImage(imageLoad, 0, 0);
    } else {
      // Set the configured background color.
      var fillStyle = layerContext.fillStyle;
      layerContext.fillStyle = cfg.backgroundColor;
      layerContext.fillRect(0, 0, width, height);
      layerContext.fillStyle = fillStyle;
    }

    /*
     * Setup the event listeners for the canvas element.
     *
     * The event handler (ev_canvas) calls the event handlers associated with 
     * the active tool (e.g. tool.mousemove).
     */
    var events = ['dblclick', 'click', 'mousedown', 'mouseup', 'mousemove', 
        'contextmenu'],
        n = events.length;

    for (var i = 0; i < n; i++) {
      bufferCanvas.addEventListener(events[i], this.ev_canvas, false);
    }

    return true;
  };

  /**
   * Initialize the Canvas buffer context. This method updates the context 
   * properties to reflect the values defined in the PaintWeb configuration 
   * file.
   * 
   * <p>Shadows support is also determined. The {@link PaintWeb#shadowSupported} 
   * value is updated accordingly.
   *
   * @private
   * @returns {Boolean} True if the initialization was successful, or false if 
   * not.
   */
  this.initContext = function () {
    var bufferContext = this.buffer.context;

    // Opera does not render shadows, at the moment.
    if (!pwlib.browser.opera && bufferContext.shadowColor && 'shadowOffsetX' in 
        bufferContext && 'shadowOffsetY' in bufferContext && 'shadowBlur' in 
        bufferContext) {
      this.shadowSupported = true;
    } else {
      this.shadowSupported = false;
    }

    var cfg = this.config,
        props = {
          fillStyle:    cfg.fillStyle,
          font:         cfg.text.fontSize + 'px ' + cfg.text.fontFamily,
          lineCap:      cfg.line.lineCap,
          lineJoin:     cfg.line.lineJoin,
          lineWidth:    cfg.line.lineWidth,
          miterLimit:   cfg.line.miterLimit,
          strokeStyle:  cfg.strokeStyle,
          textAlign:    cfg.text.textAlign,
          textBaseline: cfg.text.textBaseline
        };

    if (cfg.text.bold) {
      props.font = 'bold ' + props.font;
    }

    if (cfg.text.italic) {
      props.font = 'italic ' + props.font;
    }

    // Support Gecko 1.9.0
    if (!bufferContext.fillText && 'mozTextStyle' in bufferContext) {
      props.mozTextStyle = props.font;
    }

    for (var prop in props) {
      bufferContext[prop] = props[prop];
    }

    // shadows are only for the layer context.
    if (cfg.shadow.enable && this.shadowSupported) {
      var layerContext = this.layer.context;
      layerContext.shadowColor   = cfg.shadow.shadowColor;
      layerContext.shadowBlur    = cfg.shadow.shadowBlur;
      layerContext.shadowOffsetX = cfg.shadow.shadowOffsetX;
      layerContext.shadowOffsetY = cfg.shadow.shadowOffsetY;
    }

    return true;
  };

  /**
   * Initialization procedure which runs after the configuration, language and 
   * GUI files have loaded.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.appInit} event.
   *
   * @private
   *
   * @see pwlib.appEvent.appInit
   */
  this.initComplete = function () {
    if (!this.initCommands()) {
      this.initError(lang.errorInitCommands);
      return;
    }

    // The initial blank state of the image
    this.historyAdd();
    this.image.modified = false;

    // The global keyboard events handler implements everything needed for 
    // switching between tools and for accessing any other functionality of the 
    // Web application.
    kbListener_ = new pwlib.dom.KeyboardEventListener(this.config.guiPlaceholder,
        {keydown:  this.ev_keyboard,
         keypress: this.ev_keyboard,
         keyup:    this.ev_keyboard});

    this.updateCanvasScaling();
    this.win.addEventListener('resize', this.updateCanvasScaling, false);

    this.events.add('configChange',    this.configChangeHandler);
    this.events.add('imageSaveResult', this.imageSaveResultHandler);

    // Add the init event handler.
    if (typeof temp_.onInit === 'function') {
      _self.events.add('appInit', temp_.onInit);
      delete temp_.onInit;
    }

    this.initialized = PaintWeb.INIT_DONE;

    this.events.dispatch(new appEvent.appInit(this.initialized));
  };

  /**
   * Load all the configured drawing tools.
   * @private
   */
  this.initTools = function () {
    var id   = '',
        cfg  = this.config,
        n    = cfg.tools.length,
        base = PaintWeb.baseFolder + cfg.toolsFolder + '/';

    if (n < 1) {
      this.initError(lang.noToolConfigured);
      return;
    }

    temp_.toolsLoadQueue = n;

    for (var i = 0; i < n; i++) {
      id = cfg.tools[i];
      if (id in pwlib.tools) {
        this.toolLoaded();
      } else {
        this.scriptLoad(base + id + '.js' , this.toolLoaded);
      }
    }
  };

  /**
   * The <code>load</code> event handler for each tool script.
   * @private
   */
  this.toolLoaded = function () {
    temp_.toolsLoadQueue--;

    if (temp_.toolsLoadQueue === 0) {
      var t = _self.config.tools,
          n = t.length;

      for (var i = 0; i < n; i++) {
        if (!_self.toolRegister(t[i])) {
          _self.initError(pwlib.strf(lang.toolRegisterFailed, {id: t[i]}));
          return;
        }
      }

      _self.initExtensions();
    }
  };

  /**
   * Load all the extensions.
   * @private
   */
  this.initExtensions = function () {
    var id   = '',
        cfg  = this.config,
        n    = cfg.extensions.length,
        base = PaintWeb.baseFolder + cfg.extensionsFolder + '/';

    if (n < 1) {
      this.initComplete();
      return;
    }

    temp_.extensionsLoadQueue = n;

    for (var i = 0; i < n; i++) {
      id = cfg.extensions[i];
      if (id in pwlib.extensions) {
        this.extensionLoaded();
      } else {
        this.scriptLoad(base + id + '.js', this.extensionLoaded);
      }
    }
  };

  /**
   * The <code>load</code> event handler for each extension script.
   * @private
   */
  this.extensionLoaded = function () {
    temp_.extensionsLoadQueue--;

    if (temp_.extensionsLoadQueue === 0) {
      var e = _self.config.extensions,
          n = e.length;

      for (var i = 0; i < n; i++) {
        if (!_self.extensionRegister(e[i])) {
          _self.initError(pwlib.strf(lang.extensionRegisterFailed, {id: e[i]}));
          return;
        }
      }

      _self.initComplete();
    }
  };

  /**
   * Update the canvas scaling. This method determines the DPI and/or zoom level 
   * used by the browser to render the application. Based on these values, the 
   * canvas elements are scaled down to cancel any upscaling performed by the 
   * browser.
   *
   * <p>The {@link pwlib.appEvent.canvasSizeChange} application event is 
   * dispatched.
   */
  this.updateCanvasScaling = function () {
    var res         = _self.resolution,
        cs          = win.getComputedStyle(res.elem, null),
        image       = _self.image;
        bufferStyle = _self.buffer.canvas.style,
        layerStyle  = _self.layer.canvas.style,
        scaleNew    = 1,
        width       = parseInt(cs.width),
        height      = parseInt(cs.height);

    if (pwlib.browser.opera) {
      // Opera zoom level detection.
      // The scaling factor is sufficiently accurate for zoom levels between 
      // 100% and 200% (in steps of 10%).

      scaleNew = win.innerHeight / height;
      scaleNew = MathRound(scaleNew * 10) / 10;

    } else if (width && !isNaN(width) && width !== res.dpiOptimal) {
      // Page DPI detection. This only works in Gecko 1.9.1.

      res.dpiLocal = width;

      // The scaling factor is the same as in Gecko.
      scaleNew = MathFloor(res.dpiLocal / res.dpiOptimal);

    } else if (pwlib.browser.olpcxo && pwlib.browser.gecko) {
      // Support for the default Gecko included on the OLPC XO-1 system.
      //
      // See:
      // http://www.robodesign.ro/mihai/blog/paintweb-performance
      // http://mxr.mozilla.org/mozilla-central/source/gfx/src/thebes/nsThebesDeviceContext.cpp#725
      // dotsArePixels = false on the XO due to a hard-coded patch.
      // Thanks go to roc from Mozilla for his feedback on making this work.

      res.dpiLocal = 134; // hard-coded value, we cannot determine it

      var appUnitsPerCSSPixel  = 60, // hard-coded internally in Gecko
          devPixelsPerCSSPixel = res.dpiLocal / res.dpiOptimal; // 1.3958333333
          appUnitsPerDevPixel  = appUnitsPerCSSPixel / devPixelsPerCSSPixel; // 42.9850746278...

      scaleNew = appUnitsPerCSSPixel / MathFloor(appUnitsPerDevPixel); // 1.4285714285...

      // New in Gecko 1.9.2.
      if ('mozImageSmoothingEnabled' in layerStyle) {
        layerStyle.mozImageSmoothingEnabled 
          = bufferStyle.mozImageSmoothingEnabled = false;
      }
    }

    if (scaleNew === res.scale) {
      return;
    }

    res.scale = scaleNew;

    var styleWidth  = image.width  / res.scale * image.zoom,
        styleHeight = image.height / res.scale * image.zoom;

    image.canvasScale = styleWidth / image.width;

    // FIXME: MSIE 9 clears the Canvas element when you change the 
    // elem.style.width/height... *argh*
    bufferStyle.width  = layerStyle.width  = styleWidth  + 'px';
    bufferStyle.height = layerStyle.height = styleHeight + 'px';

    _self.events.dispatch(new appEvent.canvasSizeChange(styleWidth, styleHeight, 
          image.canvasScale));
  };

  /**
   * The Canvas events handler.
   * 
   * <p>This method determines the mouse position relative to the canvas 
   * element, after which it invokes the method of the currently active tool 
   * with the same name as the current event type. For example, for the 
   * <code>mousedown</code> event the <code><var>tool</var>.mousedown()</code> 
   * method is invoked.
   *
   * <p>The mouse coordinates are stored in the {@link PaintWeb#mouse} object.  
   * These properties take into account the current zoom level and the image 
   * scroll.
   *
   * @private
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the tool event handler executed, or false 
   * otherwise.
   */
  this.ev_canvas = function (ev) {
    if (!_self.tool) {
      return false;
    }

    switch (ev.type) {
      case 'mousedown':
        /*
         * If the mouse is down already, skip the event.
         * This is needed to allow the user to go out of the drawing canvas, 
         * release the mouse button, then come back and click to end the drawing 
         * operation.
         * Additionally, this is needed to allow extensions like MouseKeys to 
         * perform their actions during a drawing operation, even when a real 
         * mouse is used. For example, allow the user to start drawing with the 
         * keyboard (press 0) then use the real mouse to move and click to end 
         * the drawing operation.
         */
        if (_self.mouse.buttonDown) {
          return false;
        }
        _self.mouse.buttonDown = true;
        break;

      case 'mouseup':
        // Skip the event if the mouse button was not down.
        if (!_self.mouse.buttonDown) {
          return false;
        }
        _self.mouse.buttonDown = false;
    }

    /*
     * Update the event, to include the mouse position, relative to the canvas 
     * element.
     */
    if ('layerX' in ev) {
      if (_self.image.canvasScale === 1) {
        _self.mouse.x = ev.layerX;
        _self.mouse.y = ev.layerY;
      } else {
        _self.mouse.x = MathRound(ev.layerX / _self.image.canvasScale);
        _self.mouse.y = MathRound(ev.layerY / _self.image.canvasScale);
      }
    } else if ('offsetX' in ev) {
      if (_self.image.canvasScale === 1) {
        _self.mouse.x = ev.offsetX;
        _self.mouse.y = ev.offsetY;
      } else {
        _self.mouse.x = MathRound(ev.offsetX / _self.image.canvasScale);
        _self.mouse.y = MathRound(ev.offsetY / _self.image.canvasScale);
      }
    }

    // The event handler of the current tool.
    if (ev.type in _self.tool && _self.tool[ev.type](ev)) {
      ev.preventDefault();
      return true;
    } else {
      return false;
    }
  };

  /**
   * The global keyboard events handler. This makes all the keyboard shortcuts 
   * work in the web application.
   *
   * <p>This method determines the key the user pressed, based on the 
   * <var>ev</var> DOM Event object, taking into consideration any browser 
   * differences. Two new properties are added to the <var>ev</var> object:
   *
   * <ul>
   *   <li><var>ev.kid_</var> is a string holding the key and the modifiers list 
   *   (<kbd>Control</kbd>, <kbd>Alt</kbd> and/or <kbd>Shift</kbd>). For 
   *   example, if the user would press the key <kbd>A</kbd> while holding down 
   *   <kbd>Control</kbd>, then <var>ev.kid_</var> would be "Control A". If the 
   *   user would press "9" while holding down <kbd>Shift</kbd>, then 
   *   <var>ev.kid_</var> would be "Shift 9".
   *
   *   <li><var>ev.kobj_</var> holds a reference to the keyboard shortcut 
   *   definition object from the configuration. This is useful for reuse, for 
   *   passing parameters from the keyboard shortcut configuration object to the 
   *   event handler.
   * </ul>
   *
   * <p>In {@link PaintWeb.config.keys} one can setup the keyboard shortcuts.  
   * If the keyboard combination is found in that list, then the associated tool 
   * is activated.
   *
   * <p>Note: this method includes some work-around for making the image zoom 
   * keys work well both in Opera and Firefox.
   *
   * @private
   *
   * @param {Event} ev The DOM Event object.
   *
   * @see PaintWeb.config.keys The keyboard shortcuts configuration.
   * @see pwlib.dom.KeyboardEventListener The class dealing with the 
   * cross-browser differences in the DOM keyboard events.
   */
  this.ev_keyboard = function (ev) {
    // Do not continue if the key was not recognized by the lib.
    if (!ev.key_) {
      return;
    }

    if (ev.target && ev.target.nodeName) {
      switch (ev.target.nodeName.toLowerCase()) {
        case 'input':
          if (ev.type === 'keypress' && (ev.key_ === 'Up' || ev.key_ === 'Down') 
              && ev.target.getAttribute('type') === 'number') {
            _self.ev_numberInput(ev);
          }
        case 'select':
        case 'textarea':
        case 'button':
          return;
      }
    }

    // Rather ugly, but the only way, at the moment, to detect these keys in 
    // Opera and Firefox.
    if (ev.type === 'keypress' && ev.char_) {
      var isZoomKey = true,
          imageZoomKeys = _self.config.imageZoomKeys;

      // Check if this is a zoom key and execute the commands as needed.
      switch (ev.char_) {
        case imageZoomKeys['in']:
          _self.imageZoomIn(ev);
          break;

        case imageZoomKeys['out']:
          _self.imageZoomOut(ev);
          break;
        case imageZoomKeys['reset']:
          _self.imageZoomReset(ev);
          break;
        default:
          isZoomKey = false;
      }

      if (isZoomKey) {
        ev.preventDefault();
        return;
      }
    }

    // Determine the key ID.
    ev.kid_ = '';
    var i, kmods = {altKey: 'Alt', ctrlKey: 'Control', shiftKey: 'Shift'};
    for (i in kmods) {
      if (ev[i] && ev.key_ !== kmods[i]) {
        ev.kid_ += kmods[i] + ' ';
      }
    }
    ev.kid_ += ev.key_;

    // Send the keyboard event to the event handler of the active tool. If it 
    // returns true, we consider it recognized the keyboard shortcut.
    if (_self.tool && ev.type in _self.tool && _self.tool[ev.type](ev)) {
      return true;
    }

    // If there's no event handler within the active tool, or if the event 
    // handler does otherwise return false, then we continue with the global 
    // keyboard shortcuts.

    var gkey = _self.config.keys[ev.kid_];
    if (!gkey) {
      return false;
    }

    ev.kobj_ = gkey;

    // Check if the keyboard shortcut has some extension associated.
    if ('extension' in gkey) {
      var extension = _self.extensions[gkey.extension],
          method    = gkey.method || ev.type;

      // Call the extension method.
      if (method in extension) {
        extension[method].call(this, ev);
      }

    } else if ('command' in gkey && gkey.command in _self.commands) {
      // Invoke the command associated with the key.
      _self.commands[gkey.command].call(this, ev);

    } else if (ev.type === 'keydown' && 'toolActivate' in gkey) {

      // Active the tool associated to the key.
      _self.toolActivate(gkey.toolActivate, ev);

    }

    if (ev.type === 'keypress') {
      ev.preventDefault();
    }
  };

  /**
   * This is the <code>keypress</code> event handler for inputs of type=number.  
   * This function only handles cases when the key is <kbd>Up</kbd> or 
   * <kbd>Down</kbd>. For the <kbd>Up</kbd> key the input value is increased, 
   * and for the <kbd>Down</kbd> the value is decreased.
   *
   * @private
   * @param {Event} ev The DOM Event object.
   * @see PaintWeb#ev_keyboard
   */
  this.ev_numberInput = function (ev) {
    var target = ev.target;

    // Process the value.
    var val,
        max  = parseFloat(target.getAttribute('max')),
        min  = parseFloat(target.getAttribute('min')),
        step = parseFloat(target.getAttribute('step'));

    if (target.value === '' || target.value === null) {
      val = !isNaN(min) ? min : 0;
    } else {
      val = parseFloat(target.value.replace(/[,.]+/g, '.').
                                    replace(/[^0-9.\-]/g, ''));
    }

    // If target is not a number, then set the old value, or the minimum value. If all fails, set 0.
    if (isNaN(val)) {
      val = min || 0;
    }

    if (isNaN(step)) {
      step = 1;
    }

    if (ev.shiftKey) {
      step *= 2;
    }

    if (ev.key_ === 'Down') {
      step *= -1;
    }

    val += step;

    if (!isNaN(max) && val > max) {
      val = max;
    } else if (!isNaN(min) && val < min) {
      val = min;
    }

    if (val == target.value) {
      return;
    }

    target.value = val;

    // Dispatch the 'change' events to make sure that any associated event 
    // handlers pick up the changes.
    if (doc.createEvent && target.dispatchEvent) {
      var ev_change = doc.createEvent('HTMLEvents');
      ev_change.initEvent('change', true, true);
      target.dispatchEvent(ev_change);
    }
  };

  /**
   * Zoom into the image.
   *
   * @param {mixed} ev An event object which might have the <var>shiftKey</var> 
   * property. If the property evaluates to true, then the zoom level will 
   * increase twice more than normal.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see PaintWeb#imageZoomTo The method used for changing the zoom level.
   * @see PaintWeb.config.zoomStep The value used for increasing the zoom level.
   */
  this.imageZoomIn = function (ev) {
    if (ev && ev.shiftKey) {
      _self.config.imageZoomStep *= 2;
    }

    var res = _self.imageZoomTo('+');

    if (ev && ev.shiftKey) {
      _self.config.imageZoomStep /= 2;
    }

    return res;
  };

  /**
   * Zoom out of the image.
   *
   * @param {mixed} ev An event object which might have the <var>shiftKey</var> 
   * property. If the property evaluates to true, then the zoom level will 
   * decrease twice more than normal.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see PaintWeb#imageZoomTo The method used for changing the zoom level.
   * @see PaintWeb.config.zoomStep The value used for decreasing the zoom level.
   */
  this.imageZoomOut = function (ev) {
    if (ev && ev.shiftKey) {
      _self.config.imageZoomStep *= 2;
    }

    var res = _self.imageZoomTo('-');

    if (ev && ev.shiftKey) {
      _self.config.imageZoomStep /= 2;
    }

    return res;
  };

  /**
   * Reset the image zoom level to normal.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see PaintWeb#imageZoomTo The method used for changing the zoom level.
   */
  this.imageZoomReset = function (ev) {
    return _self.imageZoomTo(1);
  };

  /**
   * Change the image zoom level.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.imageZoom} application 
   * event before zooming the image. Once the image zoom is applied, the {@link 
   * pwlib.appEvent.canvasSizeChange} event is dispatched.
   *
   * @param {Number|String} level The level you want to zoom the image to.
   * 
   * <p>If the value is a number, it must be a floating point positive number, 
   * where 0.5 means 50%, 1 means 100% (normal) zoom, 4 means 400% and so on.
   *
   * <p>If the value is a string it must be "+" or "-". This means that the zoom 
   * level will increase/decrease using the configured {@link 
   * PaintWeb.config.zoomStep}.
   *
   * @returns {Boolean} True if the image zoom level changed successfully, or 
   * false if not.
   */
  this.imageZoomTo = function (level) {
    var image  = this.image,
        config = this.config,
        res    = this.resolution;

    if (!level) {
      return false;
    } else if (level === '+') {
      level = image.zoom + config.imageZoomStep;
    } else if (level === '-') {
      level = image.zoom - config.imageZoomStep;
    } else if (typeof level !== 'number') {
      return false;
    }

    if (level > config.imageZoomMax) {
      level = config.imageZoomMax;
    } else if (level < config.imageZoomMin) {
      level = config.imageZoomMin;
    }

    if (level === image.zoom) {
      return true;
    }

    var cancel = this.events.dispatch(new appEvent.imageZoom(level));
    if (cancel) {
      return false;
    }

    var styleWidth  = image.width  / res.scale * level,
        styleHeight = image.height / res.scale * level,
        bufferStyle = this.buffer.canvas.style,
        layerStyle  = this.layer.canvas.style;

    image.canvasScale = styleWidth / image.width;

    // FIXME: MSIE 9 clears the Canvas element when you change the 
    // elem.style.width/height... *argh*
    bufferStyle.width  = layerStyle.width  = styleWidth  + 'px';
    bufferStyle.height = layerStyle.height = styleHeight + 'px';

    image.zoom = level;

    this.events.dispatch(new appEvent.canvasSizeChange(styleWidth, styleHeight, 
          image.canvasScale));

    return true;
  };

  /**
   * Crop the image.
   *
   * <p>The content of the image is retained only if the browser implements the 
   * <code>getImageData</code> and <code>putImageData</code> methods.
   *
   * <p>This method dispatches three application events: {@link 
   * pwlib.appEvent.imageSizeChange}, {@link pwlib.appEvent.canvasSizeChange} 
   * and {@link pwlib.appEvent.imageCrop}. The <code>imageCrop</code> event is 
   * dispatched before the image is cropped. The <code>imageSizeChange</code> 
   * and <code>canvasSizeChange</code> events are dispatched after the image is 
   * cropped.
   *
   * @param {Number} cropX Image cropping start position on the x-axis.
   * @param {Number} cropY Image cropping start position on the y-axis.
   * @param {Number} cropWidth Image crop width.
   * @param {Number} cropHeight Image crop height.
   *
   * @returns {Boolean} True if the image was cropped successfully, or false if 
   * not.
   */
  this.imageCrop = function (cropX, cropY, cropWidth, cropHeight) {
    var bufferCanvas  = this.buffer.canvas,
        bufferContext = this.buffer.context,
        image         = this.image,
        layerCanvas   = this.layer.canvas,
        layerContext  = this.layer.context;

    cropX      = parseInt(cropX);
    cropY      = parseInt(cropY);
    cropWidth  = parseInt(cropWidth);
    cropHeight = parseInt(cropHeight);

    if (!cropWidth || !cropHeight || isNaN(cropX) || isNaN(cropY) || 
        isNaN(cropWidth) || isNaN(cropHeight) || cropX >= image.width || cropY 
        >= image.height) {
      return false;
    }

    var cancel = this.events.dispatch(new appEvent.imageCrop(cropX, cropY, 
          cropWidth, cropHeight));
    if (cancel) {
      return false;
    }

    if (cropWidth > this.config.imageWidthMax) {
      cropWidth = this.config.imageWidthMax;
    }

    if (cropHeight > this.config.imageHeightMax) {
      cropHeight = this.config.imageHeightMax;
    }

    if (cropX === 0 && cropY === 0 && image.width === cropWidth && image.height 
        === cropHeight) {
      return true;
    }

    var layerData    = null,
        bufferData   = null,
        layerState   = this.stateSave(layerContext),
        bufferState  = this.stateSave(bufferContext),
        scaledWidth  = cropWidth  * image.canvasScale,
        scaledHeight = cropHeight * image.canvasScale,
        dataWidth    = MathMin(image.width,  cropWidth),
        dataHeight   = MathMin(image.height, cropHeight),
        sumX         = cropX + dataWidth,
        sumY         = cropY + dataHeight;

    if (sumX > image.width) {
      dataWidth -= sumX - image.width;
    }
    if (sumY > image.height) {
      dataHeight -= sumY - image.height;
    }

    if (layerContext.getImageData) {
      // TODO: handle "out of memory" errors.
      try {
        layerData = layerContext.getImageData(cropX, cropY, dataWidth, 
            dataHeight);
      } catch (err) { }
    }

    if (bufferContext.getImageData) {
      try {
        bufferData = bufferContext.getImageData(cropX, cropY, dataWidth, 
            dataHeight);
      } catch (err) { }
    }

    bufferCanvas.style.width  = layerCanvas.style.width  = scaledWidth  + 'px';
    bufferCanvas.style.height = layerCanvas.style.height = scaledHeight + 'px';

    layerCanvas.width  = cropWidth;
    layerCanvas.height = cropHeight;

    if (layerData && layerContext.putImageData) {
      layerContext.putImageData(layerData, 0, 0);
    }

    this.stateRestore(layerContext, layerState);
    state = this.stateSave(bufferContext);

    bufferCanvas.width  = cropWidth;
    bufferCanvas.height = cropHeight;

    if (bufferData && bufferContext.putImageData) {
      bufferContext.putImageData(bufferData, 0, 0);
    }

    this.stateRestore(bufferContext, bufferState);

    image.width  = cropWidth;
    image.height = cropHeight;

    bufferState = layerState = layerData = bufferData = null;

    this.events.dispatch(new appEvent.imageSizeChange(cropWidth, cropHeight));
    this.events.dispatch(new appEvent.canvasSizeChange(scaledWidth, 
          scaledHeight, image.canvasScale));

    return true;
  };

  /**
   * Save the state of a Canvas context.
   *
   * @param {CanvasRenderingContext2D} context The 2D context of the Canvas 
   * element you want to save the state.
   *
   * @returns {Object} The object has all the state properties and values.
   */
  this.stateSave = function (context) {
    if (!context || !context.canvas || !this.stateProperties) {
      return false;
    }

    var stateObj = {},
        prop = null,
        n = this.stateProperties.length;

    for (var i = 0; i < n; i++) {
      prop = this.stateProperties[i];
      stateObj[prop] = context[prop];
    }

    return stateObj;
  };

  /**
   * Restore the state of a Canvas context.
   *
   * @param {CanvasRenderingContext2D} context The 2D context where you want to 
   * restore the state.
   *
   * @param {Object} stateObj The state object saved by the {@link 
   * PaintWeb#stateSave} method.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.stateRestore = function (context, stateObj) {
    if (!context || !context.canvas) {
      return false;
    }

    for (var state in stateObj) {
      context[state] = stateObj[state];
    }

    return true;
  };

  /**
   * Allow shadows. This method re-enabled shadow rendering, if it was enabled 
   * before shadows were disallowed.
   *
   * <p>The {@link pwlib.appEvent.shadowAllow} event is dispatched.
   */
  this.shadowAllow = function () {
    if (this.shadowAllowed || !this.shadowSupported) {
      return;
    }

    // Note that some daily builds of Webkit in Chromium fail to render the 
    // shadow when context.drawImage() is used (see the this.layerUpdate()).
    var context = this.layer.context,
        cfg = this.config.shadow;

    if (cfg.enable) {
      context.shadowColor   = cfg.shadowColor;
      context.shadowOffsetX = cfg.shadowOffsetX;
      context.shadowOffsetY = cfg.shadowOffsetY;
      context.shadowBlur    = cfg.shadowBlur;
    }

    this.shadowAllowed = true;

    this.events.dispatch(new appEvent.shadowAllow(true));
  };

  /**
   * Disallow shadows. This method disables shadow rendering, if it is enabled.
   *
   * <p>The {@link pwlib.appEvent.shadowAllow} event is dispatched.
   */
  this.shadowDisallow = function () {
    if (!this.shadowAllowed || !this.shadowSupported) {
      return;
    }

    if (this.config.shadow.enable) {
      var context = this.layer.context;
      context.shadowColor   = 'rgba(0,0,0,0)';
      context.shadowOffsetX = 0;
      context.shadowOffsetY = 0;
      context.shadowBlur    = 0;
    }

    this.shadowAllowed = false;

    this.events.dispatch(new appEvent.shadowAllow(false));
  };

  /**
   * Update the current image layer by moving the pixels from the buffer onto 
   * the layer. This method also adds a point into the history.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.layerUpdate = function () {
    this.layer.context.drawImage(this.buffer.canvas, 0, 0);
    this.buffer.context.clearRect(0, 0, this.image.width, this.image.height);
    this.historyAdd();

    return true;
  };

  /**
   * Add the current image layer to the history.
   *
   * <p>Once the history state has been updated, this method dispatches the 
   * {@link pwlib.appEvent.historyUpdate} event.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  // TODO: some day it would be nice to implement a hybrid history system.
  this.historyAdd = function () {
    var layerContext = this.layer.context,
        history      = this.history,
        prevPos      = history.pos;

    if (!layerContext.getImageData) {
      return false;
    }

    // We are in an undo-step, trim until the end, eliminating any possible redo-steps.
    if (prevPos < history.states.length) {
      history.states.splice(prevPos, history.states.length);
    }

    // TODO: in case of "out of memory" errors... I should show up some error.
    try {
      history.states.push(layerContext.getImageData(0, 0, this.image.width, 
            this.image.height));
    } catch (err) {
      return false;
    }

    // If we have too many history ImageDatas, remove the oldest ones
    if ('historyLimit' in this.config &&
        history.states.length > this.config.historyLimit) {

      history.states.splice(0, history.states.length 
          - this.config.historyLimit);
    }
    history.pos = history.states.length;

    this.image.modified = true;

    this.events.dispatch(new appEvent.historyUpdate(history.pos, prevPos, 
          history.pos));

    return true;
  };

  /**
   * Jump to any ImageData/position in the history.
   *
   * <p>Once the history state has been updated, this method dispatches the 
   * {@link pwlib.appEvent.historyUpdate} event.
   *
   * @param {Number|String} pos The history position to jump to.
   * 
   * <p>If the value is a number, then it must point to an existing index in the  
   * <var>{@link PaintWeb#history}.states</var> array.
   *
   * <p>If the value is a string, it must be "undo" or "redo".
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.historyGoto = function (pos) {
    var layerContext = this.layer.context,
        image        = this.image,
        history      = this.history;

    if (!history.states.length || !layerContext.putImageData) {
      return false;
    }

    var cpos = history.pos;

    if (pos === 'undo') {
      pos = cpos-1;
    } else if (pos === 'redo') {
      pos = cpos+1;
    }

    if (pos < 1 || pos > history.states.length) {
      return false;
    }

    var himg = history.states[pos-1];
    if (!himg) {
      return false;
    }

    // Each image in the history can have a different size. As such, the script 
    // must take this into consideration.
    var w = MathMin(image.width,  himg.width),
        h = MathMin(image.height, himg.height);

    layerContext.clearRect(0, 0, image.width, image.height);

    try {
      // Firefox 3 does not clip the image, if needed.
      layerContext.putImageData(himg, 0, 0, 0, 0, w, h);

    } catch (err) {
      // The workaround is to use a new canvas from which we can copy the 
      // history image without causing any exceptions.
      var tmp    = doc.createElement('canvas');
      tmp.width  = himg.width;
      tmp.height = himg.height;

      var tmp2 = tmp.getContext('2d');
      tmp2.putImageData(himg, 0, 0);

      layerContext.drawImage(tmp, 0, 0);

      tmp2 = tmp = null;
      delete tmp2, tmp;
    }

    history.pos = pos;

    this.events.dispatch(new appEvent.historyUpdate(pos, cpos, 
          history.states.length));

    return true;
  };

  /**
   * Clear the image history.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.historyUpdate} event.
   *
   * @private
   */
  this.historyReset = function () {
    this.history.pos = 0;
    this.history.states = [];

    this.events.dispatch(new appEvent.historyUpdate(0, 0, 0));
  };

  /**
   * Perform horizontal/vertical line snapping. This method updates the mouse 
   * coordinates to "snap" with the given coordinates.
   *
   * @param {Number} x The x-axis location.
   * @param {Number} y The y-axis location.
   */
  this.toolSnapXY = function (x, y) {
    var diffx = MathAbs(_self.mouse.x - x),
        diffy = MathAbs(_self.mouse.y - y);

    if (diffx > diffy) {
      _self.mouse.y = y;
    } else {
      _self.mouse.x = x;
    }
  };

  /**
   * Activate a drawing tool by ID.
   *
   * <p>The <var>id</var> provided must be of an existing drawing tool, one that  
   * has been installed.
   *
   * <p>The <var>ev</var> argument is an optional DOM Event object which is 
   * useful when dealing with different types of tool activation, either by 
   * keyboard or by mouse events. Tool-specific code can implement different 
   * functionality based on events.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.toolPreactivate} event 
   * before creating the new tool instance. Once the new tool is successfully 
   * activated, the {@link pwlib.appEvent.toolActivate} event is also 
   * dispatched.
   *
   * @param {String} id The ID of the drawing tool to be activated.
   * @param {Event} [ev] The DOM Event object.
   *
   * @returns {Boolean} True if the tool has been activated, or false if not.
   *
   * @see PaintWeb#toolRegister Register a new drawing tool.
   * @see PaintWeb#toolUnregister Unregister a drawing tool.
   *
   * @see pwlib.tools The object holding all the drawing tools.
   * @see pwlib.appEvent.toolPreactivate
   * @see pwlib.appEvent.toolActivate
   */
  this.toolActivate = function (id, ev) {
    if (!id || !(id in pwlib.tools) || typeof pwlib.tools[id] !== 'function') {
      return false;
    }

    var tool = pwlib.tools[id],
        prevId = this.tool ? this.tool._id : null;

    if (prevId && this.tool instanceof pwlib.tools[id]) {
      return true;
    }

    var cancel = this.events.dispatch(new appEvent.toolPreactivate(id, prevId));
    if (cancel) {
      return false;
    }

    var tool_obj = new tool(this, ev);
    if (!tool_obj) {
      return false;
    }

    /*
     * Each tool can implement its own mouse and keyboard events handler.
     * Additionally, tool objects can implement handlers for the deactivation 
     * and activation events.
     * Given tool1 is active and tool2 is going to be activated, then the 
     * following event handlers will be called:
     *
     * tool2.preActivate
     * tool1.deactivate
     * tool2.activate
     *
     * In the "preActivate" event handler you can cancel the tool activation by 
     * returning a value which evaluates to false.
     */

    if ('preActivate' in tool_obj && !tool_obj.preActivate(ev)) {
      tool_obj = null;
      return false;
    }

    // Deactivate the previously active tool
    if (this.tool && 'deactivate' in this.tool) {
      this.tool.deactivate(ev);
    }

    this.tool = tool_obj;

    this.mouse.buttonDown = false;

    // Besides the "constructor", each tool can also have code which is run 
    // after the deactivation of the previous tool.
    if ('activate' in this.tool) {
      this.tool.activate(ev);
    }

    this.events.dispatch(new appEvent.toolActivate(id, prevId));

    return true;
  };

  /**
   * Register a new drawing tool into PaintWeb.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.toolRegister} 
   * application event.
   *
   * @param {String} id The ID of the new tool. The tool object must exist in 
   * {@link pwlib.tools}.
   *
   * @returns {Boolean} True if the tool was successfully registered, or false 
   * if not.
   *
   * @see PaintWeb#toolUnregister allows you to unregister tools.
   * @see pwlib.tools Holds all the drawing tools.
   * @see pwlib.appEvent.toolRegister
   */
  this.toolRegister = function (id) {
    if (typeof id !== 'string' || !id) {
      return false;
    }

    // TODO: it would be very nice to create the tool instance on register, for 
    // further extensibility.

    var tool = pwlib.tools[id];
    if (typeof tool !== 'function') {
      return false;
    }

    tool.prototype._id = id;

    this.events.dispatch(new appEvent.toolRegister(id));

    if (!this.tool && id === this.config.toolDefault) {
      return this.toolActivate(id);
    } else {
      return true;
    }
  };

  /**
   * Unregister a drawing tool from PaintWeb.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.toolUnregister} 
   * application event.
   *
   * @param {String} id The ID of the tool you want to unregister.
   *
   * @returns {Boolean} True if the tool was unregistered, or false if it does 
   * not exist or some error occurred.
   *
   * @see PaintWeb#toolRegister allows you to register new drawing tools.
   * @see pwlib.tools Holds all the drawing tools.
   * @see pwlib.appEvent.toolUnregister
   */
  this.toolUnregister = function (id) {
    if (typeof id !== 'string' || !id || !(id in pwlib.tools)) {
      return false;
    }

    this.events.dispatch(new appEvent.toolUnregister(id));

    return true;
  };

  /**
   * Register a new extension into PaintWeb.
   *
   * <p>If the extension object being constructed has the 
   * <code>extensionRegister()</code> method, then it will be invoked, allowing 
   * any custom extension registration code to run. If the method returns false, 
   * then the extension will not be registered.
   *
   * <p>Once the extension is successfully registered, this method dispatches 
   * the {@link pwlib.appEvent.extensionRegister} application event.
   *
   * @param {String} id The ID of the new extension. The extension object 
   * constructor must exist in {@link pwlib.extensions}.
   *
   * @returns {Boolean} True if the extension was successfully registered, or 
   * false if not.
   *
   * @see PaintWeb#extensionUnregister allows you to unregister extensions.
   * @see PaintWeb#extensions Holds all the instances of registered extensions.
   * @see pwlib.extensions Holds all the extension classes.
   */
  this.extensionRegister = function (id) {
    if (typeof id !== 'string' || !id) {
      return false;
    }

    var func = pwlib.extensions[id];
    if (typeof func !== 'function') {
      return false;
    }

    func.prototype._id = id;

    var obj = new func(_self);

    if ('extensionRegister' in obj && !obj.extensionRegister()) {
      return false;
    }

    this.extensions[id] = obj;
    this.events.dispatch(new appEvent.extensionRegister(id));

    return true;
  };

  /**
   * Unregister an extension from PaintWeb.
   *
   * <p>If the extension object being destructed has the 
   * <code>extensionUnregister()</code> method, then it will be invoked, 
   * allowing any custom extension removal code to run.
   *
   * <p>Before the extension is unregistered, this method dispatches the {@link 
   * pwlib.appEvent.extensionUnregister} application event.
   *
   * @param {String} id The ID of the extension object you want to unregister.
   *
   * @returns {Boolean} True if the extension was removed, or false if it does 
   * not exist or some error occurred.
   *
   * @see PaintWeb#extensionRegister allows you to register new extensions.
   * @see PaintWeb#extensions Holds all the instances of registered extensions.
   * @see pwlib.extensions Holds all the extension classes.
   */
  this.extensionUnregister = function (id) {
    if (typeof id !== 'string' || !id || !(id in this.extensions)) {
      return false;
    }

    this.events.dispatch(new appEvent.extensionUnregister(id));

    if ('extensionUnregister' in this.extensions[id]) {
      this.extensions[id].extensionUnregister();
    }
    delete this.extensions[id];

    return true;
  };

  /**
   * Register a new command in PaintWeb. Commands are simple function objects 
   * which can be invoked by keyboard shortcuts or by GUI elements.
   *
   * <p>Once the command is successfully registered, this method dispatches the 
   * {@link pwlib.appEvent.commandRegister} application event.
   *
   * @param {String} id The ID of the new command.
   * @param {Function} func The command function.
   *
   * @returns {Boolean} True if the command was successfully registered, or 
   * false if not.
   *
   * @see PaintWeb#commandUnregister allows you to unregister commands.
   * @see PaintWeb#commands Holds all the registered commands.
   */
  this.commandRegister = function (id, func) {
    if (typeof id !== 'string' || !id || typeof func !== 'function' || id in 
        this.commands) {
      return false;
    }

    this.commands[id] = func;
    this.events.dispatch(new appEvent.commandRegister(id));

    return true;
  };

  /**
   * Unregister a command from PaintWeb.
   *
   * <p>Before the command is unregistered, this method dispatches the {@link 
   * pwlib.appEvent.commandUnregister} application event.
   *
   * @param {String} id The ID of the command you want to unregister.
   *
   * @returns {Boolean} True if the command was removed successfully, or false 
   * if not.
   *
   * @see PaintWeb#commandRegister allows you to register new commands.
   * @see PaintWeb#commands Holds all the registered commands.
   */
  this.commandUnregister = function (id) {
    if (typeof id !== 'string' || !id || !(id in this.commands)) {
      return false;
    }

    this.events.dispatch(new appEvent.commandUnregister(id));

    delete this.commands[id];

    return true;
  };

  /**
   * Load a script into the document.
   *
   * @param {String} url The script URL you want to insert.
   * @param {Function} [handler] The <code>load</code> event handler you want.
   */
  this.scriptLoad = function (url, handler) {
    if (!handler) {
      var elem = doc.createElement('script');
      elem.type = 'text/javascript';
      elem.src = url;
      this.elems.head.appendChild(elem);
      return;
    }

    // huh, use XHR then eval() the code.
    // browsers do not dispatch the 'load' event reliably for script elements.

    /** @ignore */
    var xhr = new XMLHttpRequest();

    /** @ignore */
    xhr.onreadystatechange = function () {
      if (!xhr || xhr.readyState !== 4) {
        return;

      } else if ((xhr.status !== 304 && xhr.status !== 200) || 
          !xhr.responseText) {
        handler(false, xhr);

      } else {
        try {
          eval.call(win, xhr.responseText);
        } catch (err) {
          eval(xhr.responseText, win);
        }
        handler(true, xhr);
      }

      xhr = null;
    };

    xhr.open('GET', url);
    xhr.send('');
  };

  /**
   * Insert a stylesheet into the document.
   *
   * @param {String} id The stylesheet ID. This is used to avoid inserting the 
   * same style in the document.
   * @param {String} url The URL of the stylesheet you want to insert.
   * @param {String} [media='screen, projection'] The media attribute.
   * @param {Function} [handler] The <code>load</code> event handler.
   */
  this.styleLoad = function (id, url, media, handler) {
    id = 'paintweb_style_' + id;

    var elem = doc.getElementById(id);
    if (elem) {
      return;
    }

    if (!media) {
      media = 'screen, projection';
    }

    elem = doc.createElement('link');

    if (handler) {
      elem.addEventListener('load', handler, false);
    }

    elem.id = id;
    elem.rel = 'stylesheet';
    elem.type = 'text/css';
    elem.media = media;
    elem.href = url;

    this.elems.head.appendChild(elem);
  };

  /**
   * Perform action undo.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see PaintWeb#historyGoto The method invoked by this command.
   */
  this.historyUndo = function () {
    return _self.historyGoto('undo');
  };

  /**
   * Perform action redo.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see PaintWeb#historyGoto The method invoked by this command.
   */
  this.historyRedo = function () {
    return _self.historyGoto('redo');
  };

  /**
   * Load an image. By loading an image the history is cleared and the Canvas 
   * dimensions are updated to fit the new image.
   *
   * <p>This method dispatches two application events: {@link 
   * pwlib.appEvent.imageSizeChange} and {@link 
   * pwlib.appEvent.canvasSizeChange}.
   *
   * @param {Element} importImage The image element you want to load into the 
   * Canvas.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.imageLoad = function (importImage) {
    if (!importImage || !importImage.width || !importImage.height || 
        importImage.nodeType !== this.ELEMENT_NODE || 
        !pwlib.isSameHost(importImage.src, win.location.host)) {
      return false;
    }

    this.historyReset();

    var layerContext = this.layer.context,
        layerCanvas  = this.layer.canvas,
        layerStyle   = layerCanvas.style,
        bufferCanvas = this.buffer.canvas,
        bufferStyle  = bufferCanvas.style,
        image        = this.image,
        styleWidth   = importImage.width  * image.canvasScale,
        styleHeight  = importImage.height * image.canvasScale,
        result       = true;

    bufferCanvas.width  = layerCanvas.width  = importImage.width;
    bufferCanvas.height = layerCanvas.height = importImage.height;
    bufferStyle.width  = layerStyle.width  = styleWidth  + 'px';
    bufferStyle.height = layerStyle.height = styleHeight + 'px';

    try {
      layerContext.drawImage(importImage, 0, 0);
    } catch (err) {
      result = false;
      bufferCanvas.width  = layerCanvas.width  = image.width;
      bufferCanvas.height = layerCanvas.height = image.height;
      styleWidth  = image.width  * image.canvasScale;
      styleHeight = image.height * image.canvasScale;
      bufferStyle.width  = layerStyle.width  = styleWidth  + 'px';
      bufferStyle.height = layerStyle.height = styleHeight + 'px';
    }

    if (result) {
      image.width  = importImage.width;
      image.height = importImage.height;
      _self.config.imageLoad = importImage;

      this.events.dispatch(new appEvent.imageSizeChange(image.width, 
            image.height));

      this.events.dispatch(new appEvent.canvasSizeChange(styleWidth, styleHeight, 
            image.canvasScale));
    }

    this.historyAdd();
    image.modified = false;

    return result;
  };

  /**
   * Clear the image.
   */
  this.imageClear = function (ev) {
    var layerContext = _self.layer.context,
        image = _self.image;

    layerContext.clearRect(0, 0, image.width, image.height);

    // Set the configured background color.
    var fillStyle = layerContext.fillStyle;
    layerContext.fillStyle = _self.config.backgroundColor;
    layerContext.fillRect(0, 0, image.width, image.height);
    layerContext.fillStyle = fillStyle;

    _self.historyAdd();
  };

  /**
   * Save the image.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.imageSave} event.
   *
   * <p><strong>Note:</strong> the "Save image" operation relies on integration 
   * extensions. A vanilla configuration of PaintWeb will simply open the the 
   * image in a new tab using a data: URL. You must have some event listener for 
   * the <code>imageSave</code> event and you must prevent the default action.
   *
   * <p>If the default action for the <code>imageSave</code> application event 
   * is not prevented, then this method will also dispatch the {@link 
   * pwlib.appEvent.imageSaveResult} application event.
   *
   * <p>Your event handler for the <code>imageSave</code> event must dispatch 
   * the <code>imageSaveResult</code> event.
   *
   * @param {String} [type="auto"] Image MIME type. This tells the browser which 
   * format to use when saving the image. If the image format type is not 
   * supported, then the image is saved as PNG.
   *
   * <p>You can use the resulting data URL to check which is the actual image 
   * format.
   *
   * <p>When <var>type</var> is "auto" then PaintWeb checks the type of the 
   * image currently loaded ({@link PaintWeb.config.imageLoad}). If the format 
   * is recognized, then the same format is used to save the image.
   *
   * @returns {Boolean} True if the operation was successful, or false if not.
   */
  this.imageSave = function (type) {
    var canvas = _self.layer.canvas,
        cfg = _self.config,
        img = _self.image,
        imageLoad = _self.config.imageLoad,
        ext = 'png', idata = null, src = null, pos;

    if (!canvas.toDataURL) {
      return false;
    }

    var extMap = {'jpg' : 'image/jpeg', 'jpeg' : 'image/jpeg', 'png' 
      : 'image/png', 'gif' : 'image/gif'};

    // Detect the MIME type of the image currently loaded.
    if (typeof type !== 'string' || !type) {
      if (imageLoad && imageLoad.src && imageLoad.src.substr(0, 5) !== 'data:') {
        src = imageLoad.src;
        pos = src.indexOf('?');
        if (pos !== -1) {
          src = src.substr(0, pos);
        }
        ext = src.substr(src.lastIndexOf('.') + 1).toLowerCase();
      }

      type = extMap[ext] || 'image/png';
    }

    // We consider that other formats than PNG do not support transparencies.  
    // Thus, we create a new Canvas element for which we set the configured 
    // background color, and we render the image onto it.
    if (type !== 'image/png') {
      canvas = doc.createElement('canvas');
      var context = canvas.getContext('2d');

      canvas.width  = img.width;
      canvas.height = img.height;

      context.fillStyle = cfg.backgroundColor;
      context.fillRect(0, 0, img.width, img.height);
      context.drawImage(_self.layer.canvas, 0, 0);

      context = null;
    }

    try {
      // canvas.toDataURL('image/jpeg', quality) fails in Gecko due to security 
      // concerns, uh-oh.
      if (type === 'image/jpeg' && !pwlib.browser.gecko) {
        idata = canvas.toDataURL(type, cfg.jpegSaveQuality);
      } else {
        idata = canvas.toDataURL(type);
      }
    } catch (err) {
      alert(lang.errorImageSave + "\n" + err);
      return false;
    }

    canvas = null;

    if (!idata || idata === 'data:,') {
      return false;
    }

    var ev = new appEvent.imageSave(idata, img.width, img.height),
        cancel = _self.events.dispatch(ev);

    if (cancel) {
      return true;
    }

    $('#img-list-search').addClass('ajax-loading-items');
    $.postJSON(_self.config.uploadCanvasURL,{img:idata}, function(data){ 
           $('#img-list-search').html(data.result);
           $('#img-list-search').removeClass('ajax-loading-items');
           return true;	          
    });

    idata = null;

    _self.events.dispatch(new appEvent.imageSaveResult(true));

    return false;
  };

  /**
   * The <code>imageSaveResult</code> application event handler. This method 
   * PaintWeb-related stuff: for example, the {@link PaintWeb.image.modified} 
   * flag is turned to false.
   *
   * @private
   *
   * @param {pwlib.appEvent.imageSaveResult} ev The application event object.
   *
   * @see {PaintWeb#imageSave} The method which allows you to save the image.
   */
  this.imageSaveResultHandler = function (ev) {
    if (ev.successful) {
      _self.image.modified = false;
    }
  };

  /**
   * Swap the fill and stroke styles. This is just like in Photoshop, if the 
   * user presses X, the fill/stroke colors are swapped.
   *
   * <p>This method dispatches the {@link pwlib.appEvent.configChange} event 
   * twice for each color (strokeStyle and fillStyle).
   */
  this.swapFillStroke = function () {
    var fillStyle     = _self.config.fillStyle,
        strokeStyle   = _self.config.strokeStyle;

    _self.config.fillStyle   = strokeStyle;
    _self.config.strokeStyle = fillStyle;

    var ev = new appEvent.configChange(strokeStyle, fillStyle, 'fillStyle', '', 
        _self.config);

    _self.events.dispatch(ev);

    ev = new appEvent.configChange(fillStyle, strokeStyle, 'strokeStyle', '', 
        _self.config);

    _self.events.dispatch(ev);
  };

  /**
   * Select all the pixels. This activates the selection tool, and selects the 
   * entire image.
   *
   * @param {Event} [ev] The DOM Event object which generated the request.
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see {pwlib.tools.selection.selectAll} The command implementation.
   */
  this.selectAll = function (ev) {
    if (_self.toolActivate('selection', ev)) {
      return _self.tool.selectAll(ev);
    } else {
      return false;
    }
  };

  /**
   * Cut the available selection. This only works when the selection tool is 
   * active and when some selection is available.
   *
   * @param {Event} [ev] The DOM Event object which generated the request.
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see {pwlib.tools.selection.selectionCut} The command implementation.
   */
  this.selectionCut = function (ev) {
    if (!_self.tool || _self.tool._id !== 'selection') {
      return false;
    } else {
      return _self.tool.selectionCut(ev);
    }
  };

  /**
   * Copy the available selection. This only works when the selection tool is 
   * active and when some selection is available.
   *
   * @param {Event} [ev] The DOM Event object which generated the request.
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see {pwlib.tools.selection.selectionCopy} The command implementation.
   */
  this.selectionCopy = function (ev) {
    if (!_self.tool || _self.tool._id !== 'selection') {
      return false;
    } else {
      return _self.tool.selectionCopy(ev);
    }
  };

  /**
   * Paste the current clipboard image. This only works when some ImageData is 
   * available in {@link PaintWeb#clipboard}.
   *
   * @param {Event} [ev] The DOM Event object which generated the request.
   * @returns {Boolean} True if the operation was successful, or false if not.
   *
   * @see {pwlib.tools.selection.clipboardPaste} The command implementation.
   */
  this.clipboardPaste = function (ev) {
    if (!_self.clipboard || !_self.toolActivate('selection', ev)) {
      return false;
    } else {
      return _self.tool.clipboardPaste(ev);
    }
  };

  /**
   * The <code>configChange</code> application event handler. This method 
   * updates the Canvas context properties depending on which configuration 
   * property changed.
   *
   * @private
   * @param {pwlib.appEvent.configChange} ev The application event object.
   */
  this.configChangeHandler = function (ev) {
    if (ev.group === 'shadow' && _self.shadowSupported && _self.shadowAllowed) {
      var context = _self.layer.context,
          cfg = ev.groupRef;

      // Enable/disable shadows
      if (ev.config === 'enable') {
        if (ev.value) {
          context.shadowColor   = cfg.shadowColor;
          context.shadowOffsetX = cfg.shadowOffsetX;
          context.shadowOffsetY = cfg.shadowOffsetY;
          context.shadowBlur    = cfg.shadowBlur;
        } else {
          context.shadowColor   = 'rgba(0,0,0,0)';
          context.shadowOffsetX = 0;
          context.shadowOffsetY = 0;
          context.shadowBlur    = 0;
        }
        return;
      }

      // Do not update any context properties if shadows are not enabled.
      if (!cfg.enable) {
        return;
      }

      switch (ev.config) {
        case 'shadowBlur':
        case 'shadowOffsetX':
        case 'shadowOffsetY':
          ev.value = parseInt(ev.value);
        case 'shadowColor':
          context[ev.config] = ev.value;
      }

    } else if (ev.group === 'line') {
      switch (ev.config) {
        case 'lineWidth':
        case 'miterLimit':
          ev.value = parseInt(ev.value);
        case 'lineJoin':
        case 'lineCap':
          _self.buffer.context[ev.config] = ev.value;
      }

    } else if (ev.group === 'text') {
      switch (ev.config) {
        case 'textAlign':
        case 'textBaseline':
          _self.buffer.context[ev.config] = ev.value;
      }

    } else if (!ev.group) {
      switch (ev.config) {
        case 'fillStyle':
        case 'strokeStyle':
          _self.buffer.context[ev.config] = ev.value;
      }
    }
  };

  /**
   * Destroy a PaintWeb instance. This method allows you to unload a PaintWeb 
   * instance. Extensions, tools and commands are unregistered, and the GUI 
   * elements are removed.
   *
   * <p>The scripts and styles loaded are not removed, since they might be used 
   * by other PaintWeb instances.
   *
   * <p>The {@link pwlib.appEvent.appDestroy} application event is dispatched 
   * before the current instance is destroyed.
   */
  this.destroy = function () {
    this.events.dispatch(new appEvent.appDestroy());

    for (var cmd in this.commands) {
      this.commandUnregister(cmd);
    }

    for (var ext in this.extensions) {
      this.extensionUnregister(ext);
    }

    for (var tool in this.gui.tools) {
      this.toolUnregister(tool);
    }

    this.gui.destroy();

    this.initialized = PaintWeb.INIT_NOT_STARTED;
  };

  this.toString = function () {
    return 'PaintWeb v' + this.version + ' (build ' + this.build + ')';
  };


  preInit();
};

/**
 * Application initialization not started.
 * @constant
 */
PaintWeb.INIT_NOT_STARTED = 0;

/**
 * Application initialization started.
 * @constant
 */
PaintWeb.INIT_STARTED = 1;

/**
 * Application initialization completed successfully.
 * @constant
 */
PaintWeb.INIT_DONE = 2;

/**
 * Application initialization failed.
 * @constant
 */
PaintWeb.INIT_ERROR = -1;

/**
 * PaintWeb base folder. This is determined automatically when the PaintWeb 
 * script is added in a page.
 * @type String
 */
PaintWeb.baseFolder = '';

(function () {
  var scripts = document.getElementsByTagName('script'),
      n = scripts.length,
      pos, src;

  // Determine the baseFolder.

  for (var i = 0; i < n; i++) {
    src = scripts[i].src;
    if (!src || !/paintweb(\.dev|\.src)?\.js/.test(src)) {
      continue;
    }

    pos = src.lastIndexOf('/');
    if (pos !== -1) {
      PaintWeb.baseFolder = src.substr(0, pos + 1);
    }

    break;
  }
})();

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

