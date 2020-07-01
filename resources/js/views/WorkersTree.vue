<template>
    <v-card :loading="loading">
        <v-card-title >
            <span class="headline" >Иарархия сотрудников</span>
        </v-card-title>
        <v-card-text>
            <v-treeview
                ref="treeReference"
                :items="workers"
                item-text="id"
                item-key="id"
                :load-children="loadChildren"
                :active.sync="active"
                :open.sync="open"
                open-on-click
                activatable
                transition
                return-object

            >
                <template v-slot:label="{ item }">
<!--                    @start="checkStart" @end="checkEnd"-->
                    <draggable :list="workers" group="node" :id="item.id" :data-parent="item.parent_id"   @start="checkStart" @end="checkEnd"  >
                        <label>
                             {{`${item.surname} ${item.name} `}} <strong>{{item.position.name}}</strong>
                        </label>
                    </draggable>
               </template>

            </v-treeview>

        </v-card-text>
        <v-card elevation="0" class="transparent">
            <v-card-text>
                <v-pagination
                    v-model="page"
                    :length="paginationLength"
                    @input="paginationInput"
                    :total-visible="8"
                ></v-pagination>
            </v-card-text>
        </v-card>
    </v-card>
</template>
<script>
    import {mapActions} from 'vuex'
    import draggable from 'vuedraggable'

    export default {
        data: () => ({
            workersData: {
                data:[],
            },
            search:'',
            loading:false,
            page:1,
            active: [],
            open: [],
            limit:10,
            draggableItem:{},
        }),
        computed:{
            workers(){
                let items = [];
                console.log(this.workersData.data);

                this.workersData.data.forEach((item)=>{
                    item.children.forEach(subItem=>{
                        subItem.children = [];
                    })
                    items.push(item);
                });
                return items;
            },
            paginationLength(){
                if(this.workers.length > 0){
                    let length = this.workersData.total / this.limit;
                    let int = Math.trunc(length);
                    return int < length?int+1 :int;
                }
                return 0;
            },
        },
        mounted() {
            this.initialize()
        },
        watch:{
            // open(){
            //     console.log(this.open);
            // }
        },
        methods:{
            ...mapActions(['fetchWorkersTree','fetchWorkersTreeChildren']),
            initialize(){
                this.loadWorkers()
            },
            loadWorkers(){
                this.loading = true;
                let payload = {
                    params:{
                        page:this.page,
                        limit:this.limit,
                    }
                };

                this.fetchWorkersTree(payload)
                    .then(response=>{
                        console.log(response.data);
                        this.workersData = response.data.workers;
                        console.log(this.workersData);
                    }).catch(error=>{
                        console.log(error)
                    }).finally(() => {
                        this.loading = false;
                    })
            },
            loadChildren(item){
                if(item.children.length > 0)
                    return;
                this.loading = true;

                return this.fetchWorkersTreeChildren({id:item.id})
                    .then(response=>{
                        console.log(response.data);
                        let data = response.data.workers;

                        if(data.length === 0){
                            console.log("NULL");
                            return;
                        }

                        data.forEach(item=>{
                            const key = item.id;
                            const parentNode = this.$refs.treeReference.nodes[key];

                            let childNode;
                            item.children.forEach(child=>{
                                child.children = [];
                                childNode = {...parentNode, item: child, vnode: null}
                                this.$refs.treeReference.nodes[child.id] = childNode
                            });
                        });

                        this.setChildren(item,data);
                        console.log('ITEM_CHILDREN',item.children);
                    })
                    .catch(error => console.log(error))
                    .finally(() => this.loading = false)
            },
            setChildren(item,children){
                const key = item.id;
                const parentNode = this.$refs.treeReference.nodes[key];

                let childNode;
                children.forEach((child) => {
                    childNode = {...parentNode, item: child, vnode: null}
                    this.$refs.treeReference.nodes[child.id] = childNode
                });

                item.children.push(...children);
            },
            paginationInput(){
                this.loadWorkers();
            },
            findTreeItem(items, id) {
                if (!items) {
                    return;
                }
                for (var i = 0; i < items.length; i++) {
                    var item = items[i];
                    // Test current object
                    // console.log(`${item.id} === ${id}`);

                    if (item.id == id) {
                        return item;
                    }
                    // Test children recursively
                    const child = this.findTreeItem(item.children, id);
                    if (child) {
                        return child;
                    }
                }
            },
            checkStart(evt) {
                console.log(evt);
                this.draggableItem = this.findTreeItem(this.workersData.data,evt.from.id);
                console.log(this.draggableItem);
            },
            checkEnd(evt) {
                console.log(evt);
                console.log("PARENT_ID: "+this.draggableItem.parent_id);

                if(this.draggableItem.parent_id){
                    let draggableItemParent = this.findTreeItem(this.workersData.data, this.draggableItem.parent_id);

                    const index = draggableItemParent.children.indexOf(this.draggableItem);
                    draggableItemParent.children.splice(index,1);
                }else{
                    const index = this.workersData.data.indexOf(this.draggableItem);
                    this.workersData.data.splice(index,1);
                }

                let newParentDraggableItem = this.findTreeItem(this.workersData.data, evt.to.id);
                this.draggableItem.parent_id = newParentDraggableItem.id;

                const index = this.open.indexOf(newParentDraggableItem);
                if(index!==-1){
                    console.log("ADD");
                    // newParentDraggableItem.children = [];
                    // setChildren
                    newParentDraggableItem.children.forEach(item=>{
                        const children = item.children;
                        item.children = [];
                        this.setChildren(item,children)
                    });
                    // this.setChildren(newParentDraggableItem,[this.draggableItem]);
                    newParentDraggableItem.children.push(this.draggableItem);
                }
            },
            // checkStart(evt) {
            //    console.log(evt);
            //    this.draggableItem = this.findTreeItem(this.workers,evt.from.id);
            //    console.log(this.draggableItem);
            // },
            // checkEnd(evt) {
            //     console.log(evt);
            //     console.log("PARENT_ID: "+this.draggableItem.parent_id);
            //     let draggableItemParent = this.findTreeItem(this.workers, this.draggableItem.parent_id);
            //     draggableItemParent.children.splice(draggableItemParent.children.indexOf(draggableItemParent),1);
            //
            //     let newParentDraggableItem = this.findTreeItem(this.workers, evt.from.id);
            //     console.log(newParentDraggableItem);
            //     newParentDraggableItem.children.push(this.draggableItem);
            // },
            // checkStart: function (evt) {
            //     var self = this;
            //     self.active = [];
            //     self.active.push(self.findTreeItem(self.workers, evt.from.id))
            // },
            // checkEnd: function (evt) {
            //     var self = this;
            //     var itemSelected = self.active[0];
            //     var fromParent = itemSelected.parent_id ? self.findTreeItem(self.workers, itemSelected.parent_id) : null;
            //     var toParent = self.findTreeItem(self.workers, evt.to.id);
            //     var objFrom = fromParent ? fromParent.children : self.workers;
            //     objFrom.splice(objFrom.indexOf(itemSelected), 1);
            //
            //     if (toParent.id === itemSelected.id) {
            //         itemSelected.parent_id = null;
            //         self.workersData.data.push(itemSelected);
            //     }
            //     else {
            //         itemSelected.parent_id = toParent.id;
            //         toParent.children.push(itemSelected);
            //     }
            //     // self.saveUser(itemSelected);
            //     //   self.active = [];
            //     return false;
            // },
        },
        components:{
            draggable
        }
    }
</script>

