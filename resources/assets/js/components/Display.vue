<template>
    <div id="display">
        <div class="box shadow">
            <div class="box-content">
                <p style="font-size: 180px">{{ messages[0].message.ticket1 }}</p>
                <p style="font-size: 100px">{{ messages[0].message.counter1 }}</p>
            </div>
        </div>
        <div class="row row-box">
            <div class="box shadow col margin-right-15">
                <div class="box-content">
                    <p style="font-size: 60px">{{ messages[0].message.ticket2 }}</p>
                    <p style="font-size: 30px">{{ messages[0].message.counter2 }}</p>
                </div>
            </div>
            <div class="box shadow col ">
                <div class="box-content">
                    <p style="font-size: 60px">{{ messages[0].message.ticket3 }}</p>
                    <p style="font-size: 30px">{{ messages[0].message.counter3 }}</p>
                </div>
            </div>
            <div class="box shadow col margin-left-15">
                <div class="box-content">
                    <p style="font-size: 60px">{{ messages[0].message.ticket4 }}</p>
                    <p style="font-size: 30px">{{ messages[0].message.counter4 }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['messages']
    }
</script>
