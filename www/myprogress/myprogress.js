var nodes;
var links;
var force;
var width;
var height;

var testJSON = [
    { id: 0, location: "google.com", riddle: "riddle", value: 1, tos: [1, 2] },
    { id: 1, location: "youtube.com", riddle: "riddle", value: 2, tos: [2] },
    { id: 2, location: "facebook.com", riddle: "riddle", value: 2, tos: [3, 4, 5] },
    { id: 3, location: "twitter.com", riddle: "riddle", value: 4, tos: [] },
    { id: 4, location: "bing.com", riddle: "riddle", value: 3, tos: [5] },
    { id: 5, location: "example.com", riddle: "riddle", value: 3, tos: [6] },
    { id: 6, location: "d3.com", riddle: "riddle", value: 3, tos: [7] }
];

function makeTree(rawJSON) {
    width = $(window).width();
    height = $(window).height();
    
    processJSON(rawJSON);
    
    var div = d3.select("body").append("div")   
            .attr("class", "tooltip")               
            .style("opacity", 0);
    
    force = d3.layout.force()
            .gravity(-0.01)
            .charge(-20)
            .linkDistance(100)
            .linkStrength(0.1)
            .size([width, height]);

    
    var svg = d3.select("#tree")
            .append("svg")
            .attr("width", width)
            .attr("height", height)
            .call(d3.behavior.zoom().on("zoom", function () {
                svg.attr("transform", "translate(" + d3.event.translate + ")" + " scale(" + d3.event.scale + ")");
            }))
            .append("g");
    
    force
        .nodes(nodes)
        .links(links);
    
    //Create all links
    var link = svg.selectAll(".link")
        .data(links)
        .enter()
        .append("line")
        .attr("class", "link")
        .style({ "stroke-width": 2, "stroke": "black" });
    
    // Create all the nodes
    var node = svg.selectAll(".node")
        .data(nodes)
        .enter()
        .append("g")
        .attr("class", "node");
    
    var circle = node
                .append("circle")
                .attr("r", 30)
                .style({"fill": "white", "stroke": "white"})
    
    // Add images to the nodes
    var red = node
                .filter(function(d) { return d.value == 3; })
                .append("image")
                .attr("xlink:href", "/resources/img/EggHackRed.png")
                .attr("width", 50)
                .attr("height", 50);
    
    var blue = node
                .filter(function(d) { return d.value == 2; })
                .append("image")
                .attr("xlink:href", "/resources/img/EggHackBlue.png")
                .attr("width", 50)
                .attr("height", 50);
    
    var green = node
                .filter(function(d) { return d.value == 1; })
                .append("image")
                .attr("xlink:href", "/resources/img/EggHackGreen.png")
                .attr("width", 50)
                .attr("height", 50);
    
    var gray = node
                .filter(function(d) { return d.value == 0; })
                .append("image")
                .attr("xlink:href", "/resources/img/EggHackGrey.png")
                .attr("width", 50)
                .attr("height", 50);
    
    var gold = node
                .filter(function(d) { return d.value == 4; })
                .append("image")
                .attr("xlink:href", "/resources/img/EggHackGold.png")
                .attr("width", 50)
                .attr("height", 50);
    
    svg.selectAll("image")
                .on("mouseover", function(d) {      
                    div.transition()        
                        .duration(200)      
                        .style("opacity", .9);      
                    div .html("Location: " + d.location + "<br/>" + "Riddle: " + d.riddle)  
                        .style("left", (d3.event.pageX) + "px")     
                        .style("top", (d3.event.pageY - 28) + "px");    
                })                  
                .on("mouseout", function(d) {       
                    div.transition()        
                        .duration(500)      
                        .style("opacity", 0);   
                });
    
    force.on("tick", function() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });
        
        circle
            .attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });
        
        svg
            .selectAll("image")
            .attr("x", function(d) { return d.x - 25; })
            .attr("y", function(d) { return d.y - 25; });
        
    });
    
    force.start();
}


function processJSON(rawJSON) {
    nodes = JSON.parse(rawJSON);
    links = [];
    
    var length = nodes.length;
    for(var i = 0; i < length; i++) {
        var node = nodes[i];
        var children = node.tos;
        if(children.length > 0) {
            for(var j = 0; j < children.length; j++) {
                var found = false;
                for(var k = 0; k < nodes.length; k++) {
                    if(children[j] == nodes[k].id) {
                        links.push({source: i, target: k});
                        found = true;
                        break;
                    }
                }
                if(!found) {
                    nodes.push({id: maxID + 1, location: "?", riddle: "?", value: 0, tos:[]});
                    links.push({source: i, target: nodes.length - 1});
                    length++;
                }
            }
        }
        nodes[i].x = width/2;
        nodes[i].y = height/2;
    }
    
    if(nodes[0]) {
        nodes[0].fixed = true;
    }
}
                           
function maxID() {
    var max = 0;
    for(var i = 0; i < nodes.length; i++) {
        if(nodes[i].id > max) {
            max = nodes[i].id;
        }
    }
    return max;
}   