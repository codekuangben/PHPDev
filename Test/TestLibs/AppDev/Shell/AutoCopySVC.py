# -*- coding: utf-8 -*-

import os;
import shutil;
import time;
import sys;

class AutoCopySVC(object):
    
    def __init__(self):
        self._SrcFullPath = None;
        self._DestFullPath = None;
        self._FileCompleteSuffix = "-Complete";
    
    def Init(self):
        pass;
    
    def Dispose(self):
        pass;
    
    def Run(self):
        while (True):
            self._CopyAllFile();
            time.sleep(60);
            
    def SetSrcFullPath(self, value):
        self._SrcFullPath = value;
        
    def SetDestFullPath(self, value):
        self._DestFullPath = value;
    
    def _GetAllFile(self):
        fileList = [];
        fullFilePath = "";
        for root, dirs, files in os.walk(self._SrcFullPath):
            for oneFile in files:
                fullFilePath = root + "/" + oneFile;
                fileList.append(fullFilePath);
                
        return fileList;
    
    def _CopyAllFile(self):
        allFileList = self._GetAllFile();
        oneFullFilePathWithCompleteFlag = None;
        destFullFileName = None;
        
        for oneFullFilePath in allFileList:
            oneFullFilePathWithCompleteFlag = oneFullFilePath + self._FileCompleteSuffix;
            
            if (oneFullFilePathWithCompleteFlag in allFileList):
                dir, fileName = os.path.split(oneFullFilePath);
                destFullFileName = self._DestFullPath + "/" + fileName;
                shutil.copyfile(oneFullFilePath, destFullFileName);
                os.remove(oneFullFilePath);
                os.remove(oneFullFilePathWithCompleteFlag);
    
if __name__ == '__main__':
    if (len(sys.argv) > 1):
        srcPath = sys.argv[1];
    else:
        srcPath = sys.path[0] + "/SVCReport";
    
    autoCopySVC = AutoCopySVC();
    autoCopySVC.SetSrcFullPath(srcPath);
    autoCopySVC.SetDestFullPath("Y:/ShaderVariantInfo");
    autoCopySVC.Init();
    autoCopySVC.Run();
    autoCopySVC.Dispose();
    autoCopySVC = None;
