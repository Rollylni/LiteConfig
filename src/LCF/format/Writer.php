<?php

/*
 *   ____          _  _          _           _
 *  |  _ \   ___  | || | \ \/ / | |  _ _    (_)
 *  | |_) ) / _ \ | || |  \  /  | | | '_ \  | |
 *  |  __ \| (_) || || |  / /   | | | | | | | |
 *  |_|  \_\\___/ |_||_| /_/    |_| |_| |_| |_|
 *                                               
 *  Copyright (c) september 2019 Rolly lni <vk.com/rollylni>
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 */ 
 
namespace LCF\format;

interface Writer {
    
    /**
     * 
     * TODO: Writer
     * 
     * @param array $data
     * 
     * @return string
     * 
     * @throws LCF\ConfigException
     */
    public function write(array $data = []);
}