<x-layout>
    <div style="background: #eef1f5; min-height: 100vh; padding: 15px;">
        <!-- Breadcrumb -->
        <div style="font-size: 11px; color: #8e9eae; margin-bottom: 15px;">
            <a href="/" style="color: #8e9eae; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#337ab7';" onmouseout="this.style.color='#8e9eae';" target="_blank"><i class="fa fa-home"></i> Home</a> <i class="fa fa-angle-right" style="margin: 0 5px; opacity: 0.5;"></i> 
            <a href="/report" style="color: #8e9eae; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#337ab7';" onmouseout="this.style.color='#8e9eae';">Reports</a> <i class="fa fa-angle-right" style="margin: 0 5px; opacity: 0.5;"></i> 
            <span style="color: #333; font-weight: 700;">Container Storage Report</span>
        </div>

        <!-- Main Portlet -->
        <div class="portlet box" style="background: #fff; border: 1px solid #e7ecf1; border-radius: 4px; overflow: hidden;">
            <div style="background: #4b4b4b; padding: 10px 15px; color: #fff; font-size: 14px; font-weight: 600;">
                Container Storage Report
            </div>
            
            <div class="portlet-body" style="padding: 15px;">
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <colgroup>
                        <col style="width: 12%;">
                        <col style="width: 88%;">
                    </colgroup>
                    <tbody>
                        <!-- Period -->
                        <tr style="border: 1px solid #e7ecf1;">
                            <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">
                                <span style="color: #ed6b75; margin-right: 3px;">*</span>Period
                            </td>
                            <td style="padding: 10px;">
                                <div style="display: flex; align-items: center; gap: 0; width: 250px;">
                                    <input type="text" value="04-01-2026 - 04-30-2026" style="flex: 1; border: 1px solid #c2cad8; border-right: none; padding: 4px 8px; font-size: 11px; height: 28px; border-radius: 2px 0 0 2px;">
                                    <button style="background: #f4f5f8; border: 1px solid #c2cad8; height: 28px; padding: 0 10px; border-radius: 0 2px 2px 0; cursor: pointer; color: #666;">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr style="height: 10px;"><td></td><td></td></tr>

                        <!-- Department Type -->
                        <tr style="border: 1px solid #e7ecf1;">
                            <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">
                                <span style="color: #ed6b75; margin-right: 3px;">*</span>Department Type
                            </td>
                            <td style="padding: 10px; font-size: 11px; color: #333;">
                                @foreach(['Ocean Import', 'Ocean Export', 'Trucker', 'Misc'] as $dept)
                                <label style="margin-right: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                    <input type="checkbox" checked style="margin: 0; width: 13px; height: 13px;"> {{ $dept }}
                                </label>
                                @endforeach
                            </td>
                        </tr>

                        <tr style="height: 10px;"><td></td><td></td></tr>

                        <!-- Office -->
                        <tr style="border: 1px solid #e7ecf1;">
                            <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">Office</td>
                            <td style="padding: 10px;">
                                <select style="width: 200px; border: 1px solid #c2cad8; padding: 4px 8px; font-size: 11px; height: 28px; border-radius: 2px;">
                                    <option>All</option>
                                    <option>MEO</option>
                                </select>
                            </td>
                        </tr>

                        <tr style="height: 10px;"><td></td><td></td></tr>

                        <!-- Party -->
                        <tr style="border: 1px solid #e7ecf1;">
                            <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">
                                <span style="color: #ed6b75; margin-right: 3px;">*</span>Party
                            </td>
                            <td style="padding: 10px;">
                                <div style="margin-bottom: 8px;">
                                    <label style="margin-right: 15px; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 5px;">
                                        <input type="radio" name="party_type" value="customer" checked> Customer
                                    </label>
                                    <label style="cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 5px;">
                                        <input type="radio" name="party_type" value="oversea_agent"> Oversea Agent
                                    </label>
                                </div>
                                <div style="position: relative; width: 200px;">
                                    <input type="text" placeholder="Select..." style="width: 100%; border: 1px solid #c2cad8; padding: 4px 25px 4px 8px; font-size: 11px; height: 28px; border-radius: 2px;">
                                    <div style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: #999; font-size: 10px;">
                                        <i class="fa fa-angle-down" style="cursor: pointer;"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr style="height: 10px;"><td></td><td></td></tr>

                        <!-- View Option -->
                        <tr style="border: 1px solid #e7ecf1;">
                            <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">View Option</td>
                            <td style="padding: 10px; font-size: 11px; color: #333;">
                                <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" style="margin: 0; width: 13px; height: 13px;"> Show containers without storage Start Date
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                <div style="padding-bottom: 10px;">
                    <button class="btn-print">Print</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-print {
            background-color: #5b9bd1;
            color: #fff;
            border: none;
            padding: 8px 24px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 2px;
            cursor: pointer;
            transition: all 0.2s;
            opacity: 0.8;
        }
        .btn-print:hover {
            opacity: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input[type="checkbox"], input[type="radio"] {
            accent-color: #4b77be;
        }
    </style>
</x-layout>
