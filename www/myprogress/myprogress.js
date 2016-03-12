var tree;

var testJSON = [
    { eggid: 0, location: google.com, value: 1, tos: [1, 2] },
    { eggid: 1, location: youtube.com, value: 2, tos: [2] },
    { eggid: 2, location: facebook.com, value: 2, tos: [3, 4, 5] },
    { eggid: 3, location: twitter.com, value: 2, tos: [5] },
    { eggid: 4, location: bing.com, value: 3, tos: [5] },
    { eggid: 5, location: example.com, value: 3, tos: [6] },
    { eggid: 6, location: d3.com, value: 3, tos: [] }
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
        .data(tree)
        .enter()
        // Appending a g element because it's needed to hold text + circle.
        // Wouldn't need if just circle/text.
        .append("g")
        .attr("class", "gnode");
}

function processJSON(rawJSON) {
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
}