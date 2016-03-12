var nodes;
var links;

var testJSON = [
    { id: 0, location: google.com, value: 1, tos: [1, 2] },
    { id: 1, location: youtube.com, value: 2, tos: [2] },
    { id: 2, location: facebook.com, value: 2, tos: [3, 4, 5] },
    { id: 3, location: twitter.com, value: 2, tos: [5] },
    { id: 4, location: bing.com, value: 3, tos: [5] },
    { id: 5, location: example.com, value: 3, tos: [6] },
    { id: 6, location: d3.com, value: 3, tos: [] }
];

function makeTree(rawJSON) {
    processJSON(rawJSON);
    
    force = d3.layout.force()
            .charge(-100)
            .linkDistance(30)
            .size([width, height]);
    
    var svg = d3.select("#tree").append("svg");
    
    force
        .nodes(tree)
        .links()
        .start();
    
    // Create all the nodes
    var node = svg.selectAll(".node")
        .data(nodes)
        .enter()
        .append("g")
        .attr("class", "node");
    
    // Add images to the nodes
    var red = node
                .filter(function(d) { return d.value == 3; })
                .append("image")
                .attr("xlink:href", "/resources/EggHackRed.png")
    
    var blue = node
                .filter(function(d) { return d.value == 2; })
                .append("image")
                .attr("xlink:href", "/resources/EggHackBlue.png")
    
    var green = node
                .filter(function(d) { return d.value == 1; })
                .append("image")
                .attr("xlink:href", "/resources/EggHackGreen.png")
    
    //Create all links
    var link = svg.selectAll(".link")
        .data(links)
        .enter()
        .append("line")
        .attr("class", "link")
    
    force.on("tick", function() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });
    });
}


function processJSON(rawJSON) {
    nodes = JSON.parse(rawJSON);
    links = [];
    
    for(var i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        var children = nodes
        for(var j = 0; j < node.tos.length; j++) {
            links.push({source: node.id, target: node.tos[j].id});
        }
    }
    
}

/*function processJSON(rawJSON) {
    var json = JSON.parse(rawJSON);
    
    //{ eggid: 0, location: google.com, value: 1, tos: [id, id, id] }
    
    if(json[0]) {
        tree = addNode(json, 0);
    }
}

function addNode(json, id) {
    for(var i = 0; i < json.length; i++) {
        if(json[i].eggid == id) {
            var node = json[i];
            var children = [];
            for(var j = 0; j < node.tos.length; j++) {
                children.add(addNode(json, node.tos[j]));
            }
            return {id: node.eggid, location: node.location, value: node.value, children: children};
        }
    }
} */