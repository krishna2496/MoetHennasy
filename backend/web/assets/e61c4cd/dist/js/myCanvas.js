    //DONE
    var wholeShelf = {};
    var saved_canvas = [];
    $(document).ready(function() {
        $('#save-canvas').click(function(e) {
            // var d = new Date();
            // canvasName = 'canvas-' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
            // set = JSON.stringify(wholeShelf);
            // if (!jQuery.isEmptyObject(JSON.parse(set))) {
            //     $('#canvasdata').val(set);
            //     $('#canvasname').val(canvasName);
            //     $('form#canvas #submit').click();
            // }
        })

        $('#restore-canvas').on('click', function(e) {
            json_data = $('#myCanvases').find("option:selected").data("value");
            obj = json_data;
            rack = Object.keys(obj).length
            if (rack > 0) {
                $('#canvas-value').val(rack)
                $('#add').click()

                rack_height = $('.height-rack')
                for (var key in obj) {
                    key_canvas = key.split('@')
                    index = Object.keys(obj).indexOf(key)
                    $(rack_height[index]).val(key_canvas[1])
                }
                $('#create').click()
            }
            //LOAD JSON DATA
        });


        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    })
    $.fn.RackCanvas = function(canvas_id) {
        var left_pos = gap;
        var drag_left_pos = gap;
        var canvas = new fabric.Canvas($(this).attr("id").toString()),
            lastAdded = window._lastAdded = [];
        canvas.selection = false;
        document.getElementById($(this).attr("id").toString()).fabric = canvas;
        $("body").on("click", ".deleteBtn", function() {
            activeCanvas = document.getElementById($($(this).siblings('.lower-canvas')).attr('id')).fabric
            selected_obj = activeCanvas.getActiveObject();
            setTimeout(function() {
                activeCanvas.discardActiveObject().renderAll();
                activeCanvas.remove(selected_obj);
                sortLeft(activeCanvas)
                $(this).remove();
                canvas.renderAll();
            }, 100);
        });
        var snap = 5;
        var currentSelObjPos = 0;
        var count_findpos = 0;
        var gap = 2;
        var activeObject, initialCanvas = '';
        canvas.on({
            'object:moving': moveHandler,
            'object:modified': modifiedHandler,
            'object:added': addHandler
        });

        // handle object when its added to Canvas
        function addHandler(e) {
            lastAdded.push(e.target);
            e.target.lockScalingX = true;
            e.target.lockScalingY = true;
            e.target['hasBorders'] = false
            e.target['cornerSize'] = 0;
            drag_pos = 2
            e.target.canvas.forEachObject(function(obj){
                if(e.target === obj) return;
                drag_pos += obj.width + gap
            })
            drag_pos = drag_pos;
            e.target.set({ left: drag_pos })
            e.target.set({ top: e.target.canvas.height - e.target.height })
            canvas.renderAll();
            var obj = e.target;
            if (initialCanvas != '' || initialCanvas != undefined) {
                if (obj.width > obj.canvas.width || obj.height > obj.canvas.height || obj.canvas.width < (e.target.left + obj.width)) {
                    // debugger
                    sortLeft(obj.canvas)
                    alert("Insufficient Space");
                    obj.canvas.remove(obj);

                    return false;
                }
            }
            // if object is too big ignore
            if (obj.currentHeight > obj.canvas.height || obj.currentWidth > obj.canvas.width) {
                return;
            }
            obj.set({ top: obj.canvas.height - obj.height })
            obj.setCoords();

            sortLeft(obj.canvas)
        }

        // haldes object while moving or dragging
        function moveHandler(options) {
            options.target.lockMovementY = true;
            $(".deleteBtn").remove();
            // options.target.setCoords();

            // Don't allow objects off the canvas
            if (options.target.left < snap) {
                options.target.set({ left: gap });
            }

            if (options.target.top < snap) {
                options.target.set({ top: gap });
            }

            if ((options.target.width + options.target.left) > (canvas.width - snap)) {
                options.target.set({ left: canvas.width - options.target.width });
            }

            if ((options.target.height + options.target.top) > (canvas.height - snap)) {
                options.target.set({ top: canvas.height - options.target.height });
            }

            options.target.setCoords();
            // Loop through objects
            canvas.forEachObject(function(obj) {

                obj_right = obj.left + obj.width
                obj_center = obj.left + (obj.width / 2)
                options_right = options.target.left + options.target.width
                if (obj === options.target) return;
                if (currentSelObjPos < options.e.offsetX) {
                    // console.log('right')
                    if (obj.left < currentSelObjPos) {
                        return
                    }
                } else {
                    // console.log('left')
                    if (obj.left > currentSelObjPos) {
                        return
                    }
                }
                if (Math.abs((options.target.top + options.target.height) - (obj.top + obj.height)) < snap) {
                    if (obj.left < options_right && options_right <= (obj_center) && currentSelObjPos < options.e.offsetX) {
                        swipe_right(options, obj); // active object is moving into right direction
                    } else if (options.target.left < obj_right && obj_center < options.target.left && currentSelObjPos > options.e.offsetX) {
                        swipe_left(options, obj); // active object is moving into left direction
                    }
                }
            });
            currentSelObjPos = options.e.offsetX;
        }
        // Handles object after moving or dragging stop
        function modifiedHandler(e) {
            var obj = e.target;
            obj.set({ top: canvas.height - obj.height })
            obj.setCoords()
            if (obj.canvas != undefined) {
                findPos(obj);
                addDeleteBtn(obj.oCoords.mt.x, obj.oCoords.mt.y, obj.width, obj)
            }
            canvas.renderAll();
        }

        // inter canvas object transfer
        // Capture Object When Starts Dragging
        canvas.on('mouse:down', function() {
            if (this.getActiveObject()) {
                startDragging()
                activeObject = $.extend({}, this.getActiveObject());
                initialCanvas = this.lowerCanvasEl.id;
                this.getActiveObject().set({ opacity: 0.6 })
            }
        });
        // when mouse up:Drop object if it is a another canvas
        $(document).on('mouseup', function(evt) {
            var pos = gap,
                sourceDelObj,
                moveFinished = false;
            if (evt.target.localName === 'canvas' && initialCanvas) {
                canvasId = $(evt.target).siblings().attr('id');
                destCanvas = document.getElementById(canvasId).fabric
                sourceCanvas = document.getElementById(initialCanvas).fabric
                sourceDelObj = sourceCanvas.getActiveObject()
                if (canvasId !== initialCanvas) {
                    destCanvas.on({ 'object:added': addHandler })
                    if (destCanvas.getObjects().length > 0) {
                        pos = sortLeft(destCanvas);
                    }
                    activeObject.set({ left: pos })
                    if (activeObject.width > destCanvas.width || activeObject.height > destCanvas.height || destCanvas.width < (activeObject.left + activeObject.width)) {
                        alert('Insufficient Space');
                    } else {
                        activeObject.set({ top: destCanvas.height - activeObject.top })
                        activeObject.setCoords();
                        moveFinished = destCanvas.add(activeObject);
                        destCanvas.setActiveObject(destCanvas.item(destCanvas.getObjects().length - 1));
                        moveFinished = true;
                    }

                    if (moveFinished != false) {
                        sourceCanvas.discardActiveObject().renderAll()
                        sourceCanvas.remove(sourceDelObj);
                        sourceCanvas.requestRenderAll();
                        sortLeft(sourceCanvas)
                    } else {
                        sourceCanvas.forEachObject(function(obj) {
                            obj.set({ opacity: 1 })
                        })
                    }
                    canvas.renderAll();
                } else {
                    sourceCanvas.forEachObject(function(obj) {
                        obj.set({ opacity: 1 })
                    })
                }
            }
            initialCanvas = '';
            activeObject = {};
        });
        //Add Delete button at to top right Corner
        function addDeleteBtn(x, y, w, obj) {
            $(".deleteBtn").remove();
            var btnLeft = x;
            var btnTop = y;
            var widthadjust = w / 2;
            btnLeft = widthadjust + btnLeft - 1
            var deleteBtn = '<img src="images/cross.png" class="deleteBtn" ' +
                'style="position:absolute;top:' + btnTop + 'px;left:' + btnLeft + 'px;cursor:pointer;"/>';
            $("#canvas-container-" + canvas_id + " .canvas-container").append(deleteBtn);
        }

        // when object is selected
        canvas.observe("object:selected", function(e) {
            $(".deleteBtn").remove();
            startDragging()
            e.target.lockScalingX = true;
            e.target.lockScalingY = true;
            e.target.lockRotation = true;
            addDeleteBtn(e.target.oCoords.mt.x, e.target.oCoords.mt.y, e.target.width, e.target);
            if (e.e != undefined) {
                currentSelObjPos = e.e.offsetX;
            }
        });

        // when Object selection Updated
        canvas.observe("selection:updated", function(e) {
            $(".deleteBtn").remove();
            count_findpos = 0;
            startDragging()
            fillStroke()

        });
        // when no object selected
        canvas.observe("selection:cleared", function(e) {
            $(".deleteBtn").remove();
            startDragging()
        });
        // Locks X,Y axis of an object
        function stopDragging(active, obj) {
            // lock object
            console.log('lock')
            active.lockMovementX = true;
            active.lockMovementY = true;
            canvas.requestRenderAll();
        }
        // Start X,Y axis movements of an Object
        function startDragging() {
            canvas.forEachObject(function(obj) {
                obj.lockMovementX = false;
                obj.lockMovementY = true;
            })
        }
        // fills stroke arround Selected Object
        function fillStroke(color = 'blue') {
            canvas.forEachObject(function(e) {
                e.set({ stroke: color, strokeWidth: 0 })
            })
            obj = canvas.getActiveObject();
            obj.set({ stroke: 'blue', strokeWidth: 1 })
            canvas.renderAll()
        }
        // check for intersection and stop overlaping of objects
        function intersectingCheck(activeObject) {
            activeObject.setCoords();
            if (typeof activeObject.refreshLast != 'boolean') {
                activeObject.refreshLast = true
            };
            //loop canvas objects
            activeObject.canvas.forEachObject(function(targ) {

                if (targ === activeObject) return; //bypass self
                //check intersections with every object in canvas
                if (activeObject.intersectsWithObject(targ) ||
                    activeObject.isContainedWithinObject(targ) ||
                    targ.isContainedWithinObject(activeObject)) {
                    //objects are intersecting - deny saving last non-intersection position and break loop
                    if (typeof activeObject.lastLeft == 'number') {
                        activeObject.left = activeObject.lastLeft;
                        activeObject.top = activeObject.lastTop;
                        activeObject.refreshLast = false;
                        return;
                    }
                } else {
                    activeObject.refreshLast = true;
                }
            });

            if (activeObject.refreshLast) {
                //save last non-intersecting position if possible
                activeObject.lastLeft = activeObject.left
                activeObject.lastTop = activeObject.top;
            }
        }

        // swipe activeobject to right with rightmost intersecting object
        function swipe_right(active, obj) {
            obj.set({ left: obj.left - active.target.width - gap })
            // active.target.bringForward()
            // setLeftAnimate(active, obj, obj.left - active.target.width - gap)
            console.log('swiping right')
            // setTimeout(function(e) {
            obj.setCoords()
            active.target.set({ left: obj.left + obj.width + gap });
            active.target.setCoords();
            if (obj.width > active.target.width) {
                stopDragging(active.target, obj)
            }
            objToSort = []
            canvas.forEachObject(function(sortObj) {
                if (active === sortObj) { return }
                if (sortObj.left < active.target.left) {
                    objToSort.push(sortObj)
                }
            });
            sortLeft(canvas, active, objToSort)
            canvas.renderAll()
            // },300)
        }

        // swipe activeobject to left with lefttmost intersecting object
        function swipe_left(active, obj) {
            obj.set({ left: obj.left + active.target.width + gap })
            // active.target.bringForward()
            // setLeftAnimate(active, obj, (obj.left + active.target.width + gap))
            console.log('swiping left')
            // setTimeout(function(e) {
            obj.setCoords()
            // setLeftAnimate(active.target,(obj.left - active.target.width - gap))
            active.target.set({ left: obj.left - active.target.width - gap });
            active.target.setCoords()
            if (obj.width > active.target.width) {
                stopDragging(active.target, obj)
            }
            canvas.renderAll()
            // },300)
        }

        function setLeftAnimate(active, obj, setLeft) {
            // obj.animate({left:setLeft}, {
            // duration: 300,
            // onChange: canvas.renderAll.bind(obj.canvas),
            // });
            obj.animate('left', setLeft, {
                onChange: obj.canvas.renderAll.bind(obj.canvas),
                duration: 300,

                onComplete: function() {
                    // active.target.sendBackwards()
                },
                easing: fabric.util.ease.easeInOutSine
            });
        }
        // Finds safe and not intersecting Position of and Object
        function findPos(obj) {
            obj.setCoords()
            sortLeft(canvas)
            canvas.forEachObject(function(o) {
                if (obj === o) return;
                if (count_findpos > 100) {
                    console.log('something went wrong ,reordering bottles')
                    sortLeft(obj.canvas)

                    count_findpos = 0

                } else {
                    if (obj.intersectsWithObject(o)) {
                        count_findpos++;

                        if (obj.left < 2) {
                            obj.left = 2;
                            console.log('wrong-left')
                        } //dont place before 0
                        if (o.left + obj.width + 2 > canvas.width) { console.log('wrong-right') } //done place off canvas
                        if (o.left <= obj.left) {
                            obj.set({ left: o.width + o.left + 2 });
                            obj.setCoords()
                            canvas.forEachObject(function(check_obj) {
                                if (obj === check_obj) return;
                                if (o === check_obj) return;
                                if (obj.intersectsWithObject(check_obj)) {
                                    obj.set({ left: check_obj.left + 2 });
                                    obj.setCoords()
                                    findPos(obj)
                                }
                            })
                            if ((o.left + obj.width + o.width + 2) > canvas.width) {
                                obj.set({ left: 2 + obj.width })
                                obj.setCoords()
                                findPos(obj)
                            }
                        } else if (o.left > obj.left) {
                            obj.set({ left: o.left - obj.width - 2 });
                            obj.setCoords()
                            canvas.forEachObject(function(check_obj) {
                                if (obj === check_obj) return;
                                if (obj.intersectsWithObject(check_obj)) {
                                    obj.set({ left: check_obj.width + 2 });
                                    obj.setCoords()
                                    findPos(obj)
                                }
                            })
                            if ((obj.left) < gap) {
                                obj.set({ left: obj.width })
                                obj.setCoords()
                                findPos(obj)
                            }
                        }
                    }
                }
            })
            sortLeft(canvas)
        }
        // For Sorting all Object by its Position,Sorting Done from Left To right
        function sortLeft(canvas, active = '', objToSort = []) {
            var arrayObj = []
            var sortObj = []
            var sortObjLeft = []
            var sortPos = 0
            // console.log(sortPos)
            if (0 < objToSort.length) {
                arrayObj = objToSort
            } else {
                canvas.forEachObject(function(obj) {
                    if (active != '') { if (active.target === obj) return; }
                    arrayObj.push(obj)
                })
            }

            arrayObj.forEach(function(e) {
                sortObjLeft.push(e.left)
            })
            sortObjLeft.sort(function(a, b) { return a - b });
            sortObjLeft.forEach(function(left, index) {
                arrayObj.forEach(function(obj, index) {
                    if (obj.left == left) {
                        sortObj.push(obj)
                    }
                })
            })

                
            sortObj.forEach(function(obj) {
                // console.log(sortPos)
                if (obj.left - sortPos >= 0) {
                    obj.set({ left: sortPos +gap })
                    obj.setCoords()
                    sortPos += obj.width +gap
                }
            })
            positionToAdd = sortObj[(sortObj.length) - 1] ? sortObj[(sortObj.length) - 1].left + sortObj[(sortObj.length) - 1].width + gap : gap
            
            return positionToAdd;
        }

        function handleDragStart(e) {

            [].forEach.call(images, function(img) {
                img.classList.remove('img_dragging');
            });
            this.classList.add('img_dragging');
        }

        function handleDragOver(e) {

            if (e.preventDefault) {
                e.preventDefault();
            }

            e.dataTransfer.dropEffect = 'copy';
            return false;
        }

        function handleDragEnter(e) {
            this.classList.add('over');
        }

        function handleDragLeave(e) {

            this.classList.remove('over');
        }

        function handleDrop(e) {


            if (e.stopPropagation) {
                e.stopPropagation();
            }
            drag_left_pos = canvas.getObjects().length > 0 ? sortLeft(canvas) : gap;
            var img = document.querySelector('#images img.img_dragging');
            var newImage = new fabric.Image(img, {
                left: drag_left_pos,
                top: canvas.height - img.height,
            });
            if (img.width < canvas.width && img.height < canvas.height && canvas.width > (drag_left_pos + img.width)) {
                canvas.add(newImage);

            } else {
                alert("Insufficient Space");
            }
            e.preventDefault();
        }

        function handleDragEnd(e) {
            // this/e.target is the source node.
            [].forEach.call(images, function(img) {
                img.classList.remove('img_dragging');
            });
        }
        if (Modernizr.draganddrop) {
            // Browser supports HTML5 DnD.
            // Bind the event listeners for the image elements
            var images = document.querySelectorAll('#images img');
            [].forEach.call(images, function(img) {
                img.addEventListener('dragstart', handleDragStart, false);
                img.addEventListener('dragend', handleDragEnd, false);
            });
            // Bind the event listeners for the canvas
            var canvasContainer = document.getElementById($(this).parent().parent().attr("id").toString());
            canvasContainer.addEventListener('dragenter', handleDragEnter, false);
            canvasContainer.addEventListener('dragover', handleDragOver, false);
            canvasContainer.addEventListener('dragleave', handleDragLeave, false);
            canvasContainer.addEventListener('drop', handleDrop, false);
        } else {
            // Replace with a fallback to a library solution.
            alert("This browser doesn't support the HTML5 Drag and Drop API.");
        }
        // for mouse hover effect over container
        container = document.getElementById($(this).parent().parent().attr("id").toString());
        container.onmouseover = container.onmouseout = handler;

        function handler(event) {

            if (event.type == 'mouseover') {
                event.target.style.borderStyle = 'dashed'
            }
            if (event.type == 'mouseout') {
                event.target.style.borderStyle = ''
            }
        }

        // save click  for save canvas
        var mySelect = $("#myCanvases");

        $('#save-canvas').click(function(e) {
            loadCanvas = document.getElementById(canvas.lowerCanvasEl.id).fabric
            sortLeft(loadCanvas)
            loadCanvas.requestRenderAll()
            var json_data = JSON.stringify(loadCanvas.toDatalessJSON());
            bottle_array = []
            json_obj = jQuery.parseJSON(json_data)
            sorted = json_obj.objects
            sorting(sorted, 'left')
            // for(var obj in sorted){
            // }
            sorted.forEach(function(item, index) {
                json_obj.objects[index] = item
                bottle = json_obj.objects[index].src
                bottle_id = bottle.match(/[\w-]+.(jpg|png|txt)/g)[0].split('.')[0]
                bottle_array.push(bottle_id)
            })

            json_canvas = json_obj
            canvas_name = loadCanvas.lowerCanvasEl.id + '@' + loadCanvas.height
            // wholeShelf[canvas_name] = json_canvas;
            wholeShelf[canvas_name] = bottle_array;
            // debugger
            var d = new Date();
            canvasName = 'canvas-' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
            set = JSON.stringify(wholeShelf);
            if (!jQuery.isEmptyObject(JSON.parse(set))) {
                $('#canvasdata').val(set);
                $('#canvasname').val(canvasName);
                $('form#canvas #submit').click();
            }
        });

        $('#restore-canvas').on('click', function(e) {
            // debugger
            canvas.clear();
            json_data = $(mySelect).find("option:selected").data("value");
            obj = json_data;
            // console.log(obj)
            rack_index = []
            for (var key in obj) {
                key_canvas = key.split('@')
                loadCanvas = document.getElementById(key_canvas[0]).fabric
                loadCanvas.clear();
                for(var o in obj[key] ){
                    bottle_obj = JSON.parse(bottle_list[obj[key][o]-1])
                    rack_index.push(bottle_obj)
                }
                    fabric.util.enlivenObjects(rack_index, function(objects) {
                         var origRenderOnAddRemove = loadCanvas.renderOnAddRemove;
                        loadCanvas.renderOnAddRemove = false;
                // debugger

                        rack_index.forEach(function(ob) {
                            // debugger
                            console.log(ob);
                            loadCanvas.add(ob);
                        });
                // debugger
                       loadCanvas.renderOnAddRemove = origRenderOnAddRemove ;
                       loadCanvas.renderAll();
                    });
                // debugger

                // loadCanvas.loadFromJSON(objectToLoad, canvas.renderAll.bind(loadCanvas), function(o, object) {
                //     loadCanvas.renderAll()
                // });
                }
            

            //LOAD JSON DATA
        });

        function sorting(json_object, key_to_sort_by) {
            function sortByKey(a, b) {
                var x = a[key_to_sort_by];
                var y = b[key_to_sort_by];
                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            }
            json_object.sort(sortByKey);
        }
    };