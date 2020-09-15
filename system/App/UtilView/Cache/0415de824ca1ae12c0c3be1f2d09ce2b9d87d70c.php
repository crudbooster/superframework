<table border="1" style="border-collapse: collapse;" cellpadding="5">
    <thead>
        <tr>
            <th>Group Segment</th>
            <th>Segment</th>
            <th>Sub Segment</th>
            <th>Nama Sales</th>
            <th>Role</th>
            <th>Email</th>
            <th>Office</th>
            <th>Region</th>
            <?php for($i=1;$i<=12;$i++): ?>
                <th>Premi Booking <?php echo e($i); ?></th>
            <?php endfor; ?>
            <?php for($i=1;$i<=12;$i++): ?>
                <th>Actual <?php echo e($i); ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['group_segments_name']); ?></td>
                <td><?php echo e($row['segments_name']); ?></td>
                <td><?php echo e($row['sub_segments_name']); ?></td>
                <td><?php echo e($row['name']); ?></td>
                <td><?php echo e($row['roles_name']); ?></td>
                <td><?php echo e($row['email']); ?></td>
                <td><?php echo e($row['branches_name']); ?></td>
                <td><?php echo e($row['regions_name']); ?></td>
                <?php for($i=1;$i<=12;$i++): ?>
                    <td><?php echo e($row['booking'.$i]); ?></td>
                <?php endfor; ?>
                <?php for($i=1;$i<=12;$i++): ?>
                    <td><?php echo e($row['actual'.$i]); ?></td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH C:\xampp73\htdocs\magi_data_gen\app\Main\Views/export_premi.blade.php ENDPATH**/ ?>